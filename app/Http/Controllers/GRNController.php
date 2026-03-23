<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybgrn;
use App\Models\Ybimmst;
use App\Services\HanaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GRNController extends Controller
{
    protected HanaService $hanaService;

    public function __construct(HanaService $hanaService)
    {
        $this->hanaService = $hanaService;
    }
    /**
     * Display a listing of GRNs
     */
    public function index(Request $request)
    {
        $query = Ybgrn::with('itemMaster');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('GBARCODE', 'like', "%{$search}%")
                    ->orWhere('GRNNO', 'like', "%{$search}%")
                    ->orWhere('GITMCODE', 'like', "%{$search}%");
            });
        }

        $grns = $query->orderBy('GDTE', 'desc')->paginate(50);

        return view('grn.index', compact('grns'));
    }

    /**
     * Show the form for creating a new GRN
     */
    public function create()
    {
        $items = Ybimmst::orderBy('ITMCODE')->get();
        return view('grn.create', compact('items'));
    }

    /**
     * Search OITM item codes for typeahead
     */
    public function itemCodes(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $limit = (int) $request->query('limit', 20);
        $limit = max(5, min($limit, 50));

        $items = $this->hanaService->searchOitmItems($search, $limit);

        return response()->json([
            'items' => array_map(function ($item) {
                return [
                    'code' => $item['ITEMCODE'] ?? '',
                    'name' => $item['ITEMNAME'] ?? '',
                ];
            }, $items),
        ]);
    }

    /**
     * Search OWHS warehouse codes for typeahead
     */
    public function warehouseCodes(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $limit = (int) $request->query('limit', 20);
        $limit = max(5, min($limit, 50));

        $warehouses = $this->hanaService->searchWarehouses($search, $limit);

        return response()->json([
            'items' => array_map(function ($whs) {
                return [
                    'code' => $whs['WHSCODE'] ?? '',
                    'name' => $whs['WHSNAME'] ?? '',
                ];
            }, $warehouses),
        ]);
    }

    /**
     * Store a newly created GRN
     */
    public function store(Request $request)
    {
        $hasBulkItems = is_array($request->input('items'));

        if ($hasBulkItems) {
            $validated = $request->validate([
                'grnno' => 'required|string|max:100',
                'gdte' => 'required|date',
                'whscode' => 'nullable|string|max:10',
                'gremark' => 'nullable|string|max:250',
                'items' => 'required|array|min:1',
                'items.*.gitmcode' => 'required|string|max:25',
                'items.*.serial' => 'required|string|max:100',
                'items.*.gbarcode' => 'required|string|max:100|distinct|unique:YBGRN,GBARCODE',
            ]);

            $missing = $this->findMissingOitmCodes(array_column($validated['items'], 'gitmcode'));
            if (!empty($missing)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['items' => 'Invalid item code(s): ' . implode(', ', $missing)]);
            }

            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $item) {
                    Ybgrn::create([
                        'GBARCODE' => $item['gbarcode'],
                        'GITMCODE' => $item['gitmcode'],
                        'GDTE' => $validated['gdte'],
                        'GRNNO' => $validated['grnno'],
                        'GREMARK' => $validated['gremark'] ?? null,
                        'WHSCODE' => $validated['whscode'] ?? null,
                        'GCRTUSR' => Auth::user()->name,
                        'GCRTDTME' => now(),
                        'GCHPRT' => 0,
                        'GCHACT' => 1,
                    ]);
                }
            });

            $barcodes = array_map(function ($item) {
                return $item['gbarcode'];
            }, $validated['items']);

            session()->put('print_barcodes', $barcodes);

            return redirect()->route('grn.index')
                ->with('success', 'GRN items created successfully.');
        }

        $validated = $request->validate([
            'gbarcode' => 'required|string|max:100|unique:YBGRN,GBARCODE',
            'gitmcode' => 'required|string|max:25',
            'gdte' => 'required|date',
            'grnno' => 'required|string|max:100',
            'gremark' => 'nullable|string|max:250',
            'whscode' => 'nullable|string|max:10',
        ]);

        $missing = $this->findMissingOitmCodes([$validated['gitmcode']]);
        if (!empty($missing)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['gitmcode' => 'Invalid item code: ' . $missing[0]]);
        }

        Ybgrn::create([
            'GBARCODE' => $validated['gbarcode'],
            'GITMCODE' => $validated['gitmcode'],
            'GDTE' => $validated['gdte'],
            'GRNNO' => $validated['grnno'],
            'GREMARK' => $validated['gremark'] ?? null,
            'WHSCODE' => $validated['whscode'] ?? null,
            'GCRTUSR' => Auth::user()->name,
            'GCRTDTME' => now(),
            'GCHPRT' => 0,
            'GCHACT' => 1,
        ]);

        session()->put('print_barcodes', [$validated['gbarcode']]);

        return redirect()->route('grn.index')
            ->with('success', 'GRN created successfully.');
    }

    /**
     * Validate item codes against OITM
     */
    protected function findMissingOitmCodes(array $codes): array
    {
        $missing = [];
        $uniqueCodes = array_unique(array_filter(array_map('trim', $codes)));

        foreach ($uniqueCodes as $code) {
            if (!$this->hanaService->getOitmItem($code)) {
                $missing[] = $code;
            }
        }

        return $missing;
    }

    /**
     * Print QR stickers for selected GRNs (works for both saved and unsaved barcodes)
     */
    public function printSelected(Request $request)
    {
        $barcodes = $request->input('barcodes', '');
        
        if (is_string($barcodes)) {
            $barcodes = array_filter(array_map('trim', explode(',', $barcodes)));
        }

        if (!is_array($barcodes) || empty($barcodes)) {
            return redirect()->route('grn.index')
                ->with('error', 'No GRN items selected for printing.');
        }

        // Try to fetch from database first
        $items = Ybgrn::whereIn('GBARCODE', $barcodes)->get();

        if ($items->isNotEmpty()) {
            // Barcodes exist in the database — use DB data
            $stickers = $items->map(function ($grn) {
                $barcode = $grn->GBARCODE ?? $grn->gbarcode ?? '';
                $parts = array_map('trim', explode('-', $barcode));

                return [
                    'barcode' => $barcode,
                    'serial' => $parts[0] ?? '',
                    'item_code' => $parts[1] ?? ($grn->GITMCODE ?? $grn->gitmcode ?? ''),
                    'grn_no' => $parts[2] ?? ($grn->GRNNO ?? $grn->grnno ?? ''),
                ];
            })->values();
        } else {
            // Barcodes not yet saved — parse from the barcode string (SERIAL-ItemCode-GRNNO)
            $stickers = collect($barcodes)->map(function ($barcode) {
                $parts = array_map('trim', explode('-', $barcode));

                return [
                    'barcode' => $barcode,
                    'serial' => $parts[0] ?? '',
                    'item_code' => $parts[1] ?? '',
                    'grn_no' => $parts[2] ?? '',
                ];
            })->values();
        }

        return view('grn.print-stickers', compact('stickers'));
    }

    /**
     * Print QR stickers for GRN barcodes
     */
    public function printStickers(Request $request)
    {
        $barcodes = $request->input('barcodes');

        if (is_string($barcodes)) {
            $barcodes = array_filter(array_map('trim', explode(',', $barcodes)));
        }

        if (!is_array($barcodes) || empty($barcodes)) {
            $barcodes = session('print_barcodes', []);
        }

        if (empty($barcodes)) {
            return redirect()->route('grn.index')
                ->with('error', 'No GRN items selected for printing.');
        }

        $items = Ybgrn::whereIn('GBARCODE', $barcodes)->get();
        $stickers = $items->map(function ($grn) {
            $barcode = $grn->GBARCODE ?? $grn->gbarcode ?? '';
            $parts = array_map('trim', explode('-', $barcode));

            return [
                'barcode' => $barcode,
                'serial' => $parts[0] ?? '',
                'item_code' => $parts[1] ?? ($grn->GITMCODE ?? $grn->gitmcode ?? ''),
                'grn_no' => $parts[2] ?? ($grn->GRNNO ?? $grn->grnno ?? ''),
            ];
        })->values();

        session()->forget('print_barcodes');

        return view('grn.print-stickers', compact('stickers'));
    }

    /**
     * Display the specified GRN
     */
    public function show($gbarcode)
    {
        $grn = Ybgrn::with('itemMaster')->findOrFail($gbarcode);
        return view('grn.show', compact('grn'));
    }

    /**
     * Print GRN
     */
    public function print($gbarcode)
    {
        $grn = Ybgrn::with('itemMaster')->findOrFail($gbarcode);
        
        // Mark as printed
        $grn->update(['gchprt' => 1]);

        return view('grn.print', compact('grn'));
    }

    /**
     * Search GRN by barcode
     */
    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->input('barcode');
        $grn = Ybgrn::with('itemMaster')->where('GBARCODE', $barcode)->first();

        if (!$grn) {
            return redirect()->back()
                ->with('error', 'GRN not found.');
        }

        return view('grn.show', compact('grn'));
    }
}
