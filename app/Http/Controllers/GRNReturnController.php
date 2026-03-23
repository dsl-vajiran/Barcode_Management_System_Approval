<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybgrn;
use App\Models\Ybgrtn;
use App\Models\Ybimmst;
use Illuminate\Support\Facades\Auth;

class GRNReturnController extends Controller
{
    /**
     * Display GRN return page
     */
    public function index()
    {
        return view('grn-return.index');
    }

    /**
     * Search GRN for return
     */
    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->input('barcode');
        $grn = Ybgrn::with('itemMaster')->where('gbarcode', $barcode)->first();

        if (!$grn) {
            return redirect()->back()
                ->with('error', 'GRN not found.');
        }

        // Check if already returned
        $existingReturn = Ybgrtn::where('gbarcode', $barcode)->first();
        if ($existingReturn) {
            return redirect()->back()
                ->with('error', 'This GRN has already been returned.');
        }

        return view('grn-return.show', compact('grn'));
    }

    /**
     * Process GRN return
     */
    public function return(Request $request)
    {
        $request->validate([
            'gbarcode' => 'required|string|exists:ybgrn,gbarcode',
            'reason' => 'required|string|max:250',
        ]);

        $barcode = $request->input('gbarcode');
        $grn = Ybgrn::where('gbarcode', $barcode)->first();

        if (!$grn) {
            return redirect()->back()
                ->with('error', 'GRN not found.');
        }

        // Check if already returned
        $existingReturn = Ybgrtn::where('gbarcode', $barcode)->first();
        if ($existingReturn) {
            return redirect()->back()
                ->with('error', 'This GRN has already been returned.');
        }

        // Create return record
        Ybgrtn::create([
            'gbarcode' => $grn->gbarcode,
            'gitmcode' => $grn->gitmcode,
            'gdte' => $grn->gdte,
            'grnno' => $grn->grnno,
            'gcrtusr' => Auth::user()->name,
            'gcrtdtme' => $grn->gcrtdtme,
            'gremark' => $grn->gremark,
            'gchprt' => $grn->gchprt,
            'gchact' => 0, // Mark as inactive
            'reason' => $request->input('reason'),
            'rtndtme' => now(),
            'whscode' => $grn->whscode,
        ]);

        // Mark GRN as inactive
        $grn->update(['gchact' => 0]);

        return redirect()->route('grn-return.index')
            ->with('success', 'GRN returned successfully.');
    }
}
