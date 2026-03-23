<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybissue;
use Illuminate\Support\Facades\DB;

class ApprovedBarcodeController extends Controller
{
    /**
     * Display a listing of approved barcodes
     */
    public function index(Request $request)
    {
        // Barcodes with both SALEDTME and IAPRDTE filled
        $query = Ybissue::whereNotNull('SALEDTME')
            ->whereNotNull('IAPRDTE')
            ->where('ICHAPR', 1);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('IBARCODE', 'like', "%{$search}%")
                  ->orWhere('INVNO', 'like', "%{$search}%")
                  ->orWhere('ITMNME', 'like', "%{$search}%");
            });
        }

        $approvedIssues = $query->orderBy('IAPRDTE', 'desc')->paginate(50)->withQueryString();

        return view('approved-barcodes.index', compact('approvedIssues'));
    }
}
