<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybissue;
use Illuminate\Support\Facades\DB;

class BatteryReturnController extends Controller
{
    /**
     * Display battery return page
     */
    public function index()
    {
        return view('battery-return.index');
    }

    /**
     * Process battery return
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
        if ($issue->ichsale == 0 && $issue->location == 'WAREHOUSE') {
            return redirect()->back()
                ->with('error', 'This battery is already in warehouse.');
        }

        // Update issue record to mark as returned
        $issue->update([
            'location' => 'WAREHOUSE',
            'ichsale' => 0,
        ]);

        return redirect()->route('battery-return.index')
            ->with('success', 'Battery returned to warehouse successfully.');
    }

    /**
     * Search barcode for return
     */
    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->input('barcode');
        $issue = Ybissue::where('ibarcode', $barcode)->first();

        if (!$issue) {
            return redirect()->back()
                ->with('error', 'Barcode not found in issued items.');
        }

        return view('battery-return.show', compact('issue'));
    }
}
