<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybissue;
use App\Models\Ybgrn;
use App\Models\Ybimmst;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemIssueController extends Controller
{
    /**
     * Display a listing of issued items
     */
    public function index(Request $request)
    {
        $query = Ybissue::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ibarcode', 'like', "%{$search}%")
                  ->orWhere('invno', 'like', "%{$search}%")
                  ->orWhere('itmnme', 'like', "%{$search}%");
            });
        }

        $issues = $query->orderBy('isudtme', 'desc')->paginate(50);

        return view('item-issue.index', compact('issues'));
    }

    /**
     * Show the form for creating a new item issue
     */
    public function create()
    {
        return view('item-issue.create');
    }

    /**
     * Store a newly created item issue
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ibarcode' => 'required|string|max:100|unique:ybissue,ibarcode',
            'invno' => 'required|string|max:25',
            'itmnme' => 'required|string|max:100',
            'itmmod' => 'required|string|max:100',
            'itmamp' => 'nullable|string|max:25',
            'f_war' => 'nullable|integer',
            'pa_war' => 'nullable|integer',
            'remark' => 'nullable|string|max:250',
            'prphase' => 'nullable|string|max:250',
            'brand' => 'required|string|max:150',
            'iremark' => 'nullable|string|max:250',
            'fncusnm' => 'nullable|string|max:250',
            'fncustp' => 'nullable|string|max:250',
            'location' => 'nullable|string|max:100',
        ]);

        // Check if barcode exists in GRN
        $grn = Ybgrn::where('gbarcode', $validated['ibarcode'])->first();
        
        if ($grn) {
            // Get item details from GRN
            $item = Ybimmst::where('itmcode', $grn->gitmcode)->first();
            if ($item) {
                $validated['f_war'] = $item->f_war ?? $validated['f_war'];
                $validated['pa_war'] = $item->pa_war ?? $validated['pa_war'];
                $validated['remark'] = $item->remark ?? $validated['remark'];
                $validated['prphase'] = $item->prphase ?? $validated['prphase'];
            }
        }

        $validated['isudtme'] = now();
        $validated['ichsale'] = 0;
        $validated['ichapr'] = 0;

        Ybissue::create($validated);

        return redirect()->route('item-issue.index')
            ->with('success', 'Item issued successfully.');
    }

    /**
     * Issue item by scanning barcode
     */
    public function issueByBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'invno' => 'required|string|max:25',
            'location' => 'nullable|string|max:100',
            'iremark' => 'nullable|string|max:250',
            'fncusnm' => 'nullable|string|max:250',
            'fncustp' => 'nullable|string|max:250',
        ]);

        $barcode = $request->input('barcode');

        // Check if already issued
        $existingIssue = Ybissue::where('ibarcode', $barcode)->first();
        if ($existingIssue) {
            return redirect()->back()
                ->with('error', 'This barcode is already issued.');
        }

        // Find in GRN
        $grn = Ybgrn::where('gbarcode', $barcode)->first();
        if (!$grn) {
            return redirect()->back()
                ->with('error', 'Barcode not found in GRN. Please create GRN first.');
        }

        // Get item details
        $item = Ybimmst::where('itmcode', $grn->gitmcode)->first();
        if (!$item) {
            return redirect()->back()
                ->with('error', 'Item master not found for this barcode.');
        }

        // Create issue record
        Ybissue::create([
            'ibarcode' => $barcode,
            'invno' => $request->input('invno'),
            'itmnme' => $item->itmnme,
            'itmmod' => $item->itmmod,
            'itmamp' => $item->itmamp,
            'f_war' => $item->f_war,
            'pa_war' => $item->pa_war,
            'remark' => $item->remark,
            'prphase' => $item->prphase,
            'brand' => $item->brand,
            'isudtme' => now(),
            'iremark' => $request->input('iremark'),
            'ichsale' => 0,
            'ichapr' => 0,
            'fncusnm' => $request->input('fncusnm'),
            'fncustp' => $request->input('fncustp'),
            'location' => $request->input('location'),
        ]);

        return redirect()->route('item-issue.index')
            ->with('success', 'Item issued successfully.');
    }

    /**
     * Display the specified item issue
     */
    public function show($ibarcode)
    {
        $issue = Ybissue::findOrFail($ibarcode);
        return view('item-issue.show', compact('issue'));
    }
}
