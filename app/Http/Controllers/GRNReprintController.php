<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybgrn;
use App\Models\Ybimmst;

class GRNReprintController extends Controller
{
    /**
     * Display GRN reprint page
     */
    public function index()
    {
        return view('grn-reprint.index');
    }

    /**
     * Search GRN for reprint
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

        return view('grn-reprint.show', compact('grn'));
    }

    /**
     * Reprint GRN
     */
    public function reprint($gbarcode)
    {
        $grn = Ybgrn::with('itemMaster')->findOrFail($gbarcode);
        
        // Mark as printed
        $grn->update(['gchprt' => 1]);

        return view('grn-reprint.print', compact('grn'));
    }
}
