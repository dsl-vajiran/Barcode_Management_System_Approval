<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybgrn;
use App\Models\Ybimmst;
use App\Models\Ybissue;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
{
    /**
     * Display barcode search page
     */
    public function index()
    {
        return view('barcode.index');
    }

    /**
     * Search for barcode across all tables
     * Searches in: YBGRN, YBIMMST, YBISSUE
     */
    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->input('barcode');
        $data = [];

        // Search in YBGRN (GRN Detail)
        $grn = Ybgrn::where('gbarcode', $barcode)->first();
        if ($grn) {
            $itemMaster = Ybimmst::where('itmcode', $grn->gitmcode)->first();
            $data['type'] = 'grn';
            $data['grn'] = $grn;
            $data['item'] = $itemMaster;
            return view('barcode.show', $data);
        }

        // Search in YBISSUE (Item Issue)
        $issue = Ybissue::where('ibarcode', $barcode)->first();
        if ($issue) {
            $data['type'] = 'issue';
            $data['issue'] = $issue;
            
            // Try to get GRN info if available
            $grn = Ybgrn::where('gbarcode', $barcode)->first();
            $data['grn'] = $grn;
            
            return view('barcode.show', $data);
        }

        // Search in YBIMMST (Item Master) - if barcode matches item code
        $item = Ybimmst::where('itmcode', $barcode)->first();
        if ($item) {
            $data['type'] = 'item';
            $data['item'] = $item;
            return view('barcode.show', $data);
        }

        return redirect()->back()
            ->with('error', 'Barcode not found in the system.');
    }

    /**
     * Display barcode details
     */
    public function show($barcode)
    {
        // Try to find in GRN first
        $grn = Ybgrn::where('gbarcode', $barcode)->first();
        if ($grn) {
            $itemMaster = Ybimmst::where('itmcode', $grn->gitmcode)->first();
            return view('barcode.show', [
                'type' => 'grn',
                'grn' => $grn,
                'item' => $itemMaster
            ]);
        }

        // Try to find in Issue
        $issue = Ybissue::where('ibarcode', $barcode)->first();
        if ($issue) {
            return view('barcode.show', [
                'type' => 'issue',
                'issue' => $issue
            ]);
        }

        return redirect()->route('barcode.index')
            ->with('error', 'Barcode not found.');
    }

    /**
     * Return battery to warehouse
     */
    public function return(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->input('barcode');

        // Find the issue record
        $issue = Ybissue::where('ibarcode', $barcode)->first();

        if (!$issue) {
            return redirect()->back()
                ->with('error', 'Barcode not found in issued items.');
        }

        // Check if already returned
        if ($issue->ichsale == 0 && !$issue->location) {
            return redirect()->back()
                ->with('error', 'This item is already in warehouse.');
        }

        // Update issue record to mark as returned
        $issue->update([
            'location' => 'WAREHOUSE',
            'ichsale' => 0,
        ]);

        return redirect()->route('barcode.index')
            ->with('success', 'Battery returned to warehouse successfully.');
    }
}
