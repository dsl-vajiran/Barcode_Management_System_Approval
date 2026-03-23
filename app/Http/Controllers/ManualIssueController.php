<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybismanu;
use App\Models\Ybimmst;
use Illuminate\Support\Facades\Auth;

class ManualIssueController extends Controller
{
    /**
     * Display a listing of manual issues
     */
    public function index(Request $request)
    {
        $query = Ybismanu::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ibarcode', 'like', "%{$search}%")
                  ->orWhere('invno', 'like', "%{$search}%")
                  ->orWhere('itmnme', 'like', "%{$search}%");
            });
        }

        $issues = $query->orderBy('isudtme', 'desc')->paginate(50);

        return view('manual-issue.index', compact('issues'));
    }

    /**
     * Show the form for creating a new manual issue
     */
    public function create()
    {
        $items = Ybimmst::orderBy('itmcode')->get();
        return view('manual-issue.create', compact('items'));
    }

    /**
     * Store a newly created manual issue
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ibarcode' => 'required|string|max:100|unique:ybismanu,ibarcode',
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
            'isudtme' => 'nullable|date',
        ]);

        if (!$validated['isudtme']) {
            $validated['isudtme'] = now();
        }

        $validated['ichsale'] = 0;
        $validated['ichapr'] = 0;

        Ybismanu::create($validated);

        return redirect()->route('manual-issue.index')
            ->with('success', 'Manual issue created successfully.');
    }

    /**
     * Display the specified manual issue
     */
    public function show($ibarcode)
    {
        $issue = Ybismanu::findOrFail($ibarcode);
        return view('manual-issue.show', compact('issue'));
    }

    /**
     * Get item details for manual issue
     */
    public function getItemDetails($itmcode)
    {
        $item = Ybimmst::findOrFail($itmcode);
        return response()->json($item);
    }
}
