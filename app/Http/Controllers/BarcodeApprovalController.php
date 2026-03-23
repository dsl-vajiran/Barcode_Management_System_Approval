<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybissue;
use App\Models\Ybismanu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarcodeApprovalController extends Controller
{
    /**
     * Display a listing of pending approvals
     */
    public function index(Request $request)
    {
        $query = Ybissue::where('ICHAPR', 0);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('IBARCODE', 'like', "%{$search}%")
                  ->orWhere('INVNO', 'like', "%{$search}%")
                  ->orWhere('ITMNME', 'like', "%{$search}%");
            });
        }

        $pendingIssues = $query->orderBy('ISUDTME', 'desc')->paginate(50)->withQueryString();

        return view('barcode-approval.index', compact('pendingIssues'));
    }

    /**
     * Approve barcode
     */
    public function approve(Request $request, $ibarcode)
    {
        $updated = Ybissue::where('IBARCODE', $ibarcode)
            ->where('ICHAPR', 0)
            ->update([
                'ICHAPR' => 1,
                'IAPRDTE' => now(),
            ]);

        if ($updated === 0) {
            $exists = Ybissue::where('IBARCODE', $ibarcode)->exists();
            return redirect()->back()->with(
                'error',
                $exists ? 'This barcode is already approved.' : 'Barcode not found.'
            );
        }

        $this->logApproval($ibarcode, null);

        return redirect()->route('barcode-approval.index')
            ->with('success', 'Barcode approved successfully.');
    }

    /**
     * Approve multiple barcodes
     */
    public function approveMultiple(Request $request)
    {
        $request->validate([
            'barcodes' => 'required|array',
            'barcodes.*' => 'required|string',
        ]);

        $barcodes = $request->input('barcodes');
        $approved = 0;

        foreach ($barcodes as $barcode) {
            $updated = Ybissue::where('IBARCODE', $barcode)
                ->where('ICHAPR', 0)
                ->update([
                    'ICHAPR' => 1,
                    'IAPRDTE' => now(),
                ]);

            if ($updated > 0) {
                $this->logApproval($barcode, null);
                $approved++;
            }
        }

        return redirect()->route('barcode-approval.index')
            ->with('success', "{$approved} barcode(s) approved successfully.");
    }

    /**
     * Display the specified barcode for approval
     */
    public function show($ibarcode)
    {
        $issue = Ybissue::where('IBARCODE', $ibarcode)->firstOrFail();
        return view('barcode-approval.show', compact('issue'));
    }

    /**
     * Search barcode via AJAX
     */
    public function searchBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        if (!$barcode) {
            return response()->json(['error' => 'Barcode is required'], 400);
        }

        $issue = Ybissue::select('IBARCODE', 'INVNO', 'ICHAPR')->where('IBARCODE', $barcode)->first();

        if (!$issue) {
            return response()->json(['error' => 'Barcode not found'], 404);
        }

        if ($issue->ichapr == 1) {
            return response()->json(['error' => 'This barcode is already approved'], 400);
        }

        // Fetch dealer name from OINV table
        $dealerName = null;
        try {
            $oinv = DB::connection('hana')
                ->table('OINV')
                ->select('CardName')
                ->where('DocNum', $issue->invno)
                ->first();
            
            $dealerName = $oinv ? $oinv->cardname : 'N/A';
        } catch (\Exception $e) {
            \Log::warning('Could not fetch dealer name from OINV', ['error' => $e->getMessage()]);
            $dealerName = 'N/A';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ibarcode' => $issue->ibarcode,
                'invno' => $issue->invno,
                'dealer' => $dealerName,
            ]
        ]);
    }

    /**
     * Store approval with form data
     */
    public function store(Request $request)
    {
        $maxSaleDate = now();
        $minSaleDate = $maxSaleDate->copy()->subYears(2);

        $validated = $request->validate([
            'ibarcode' => 'required|string',
            'saledtme' => [
                'required',
                'date',
                'after_or_equal:' . $minSaleDate->format('Y-m-d H:i:s'),
                'before_or_equal:' . $maxSaleDate->format('Y-m-d H:i:s'),
            ],
            'iremark' => 'nullable|string|max:500',
            'fncusnm' => 'nullable|string|max:200',
            'fncustp' => 'nullable|string|max:200',
        ], [
            'saledtme.after_or_equal' => 'Sale date must be within the last 2 years.',
            'saledtme.before_or_equal' => 'Sale date cannot be greater than today.',
        ]);

        try {
            $updated = Ybissue::where('IBARCODE', $validated['ibarcode'])
                ->where('ICHAPR', 0)
                ->update([
                    'SALEDTME' => $validated['saledtme'],
                    'IREMARK' => $validated['iremark'],
                    'FNCUSNM' => $validated['fncusnm'] ?? 'N/A',
                    'FNCUSTP' => $validated['fncustp'] ?? 'N/A',
                    'ICHAPR' => 1,
                    'IAPRDTE' => now(),
                ]);

            if ($updated === 0) {
                $exists = Ybissue::where('IBARCODE', $validated['ibarcode'])->exists();
                return redirect()->back()->with(
                    'error',
                    $exists ? 'This barcode is already approved' : 'Barcode not found'
                );
            }

            $this->logApproval($validated['ibarcode'], $validated['iremark'] ?? null);

            return redirect()->route('barcode-approval.index')
                ->with('success', 'Barcode approved successfully with all details.');
        } catch (\Exception $e) {
            \Log::error('Error updating approval:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error saving approval: ' . $e->getMessage());
        }
    }

    private function logApproval(string $barcode, ?string $remark): void
    {
        try {
            $logRemark = $barcode . ': ' . ($remark ?? '');
            DB::connection('hana')->table('YB_LOG')->insert([
                'TRNTYP' => 'ITEM APPROVED',
                'REMARKS' => $logRemark,
                'DTETME' => now(),
                'USRNME' => Auth::user()?->name,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error inserting YB_LOG:', ['error' => $e->getMessage()]);
        }
    }
}
