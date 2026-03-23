<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HanaService;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemMasterController extends Controller
{
    protected HanaService $hanaService;

    public function __construct(HanaService $hanaService)
    {
        $this->hanaService = $hanaService;
    }

    /**
     * Display a listing of items from SAP HANA
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = 20;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;

        // Fetch data from HANA
        $items = $this->hanaService->searchItems($search, $perPage, $offset);
        $total = $this->hanaService->countItems($search);

        // Convert to objects for blade template compatibility
        $itemObjects = collect($items)->map(function ($item) {
            return (object) [
                'itmcode' => $item['ITMCODE'] ?? null,
                'itmnme' => $item['ITMNME'] ?? null,
                'itmmod' => $item['ITMMOD'] ?? null,
                'itmamp' => $item['ITMAMP'] ?? null,
                'f_war' => $item['F_WAR'] ?? null,
                'pa_war' => $item['PA_WAR'] ?? null,
                'remark' => $item['REMARK'] ?? null,
                'brand' => $item['BRAND'] ?? null,
            ];
        });

        // Create paginator
        $paginator = new LengthAwarePaginator(
            $itemObjects,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('item-master.index', ['items' => $paginator]);
    }

    /**
     * Show the form for creating a new item
     */
    public function create()
    {
        return view('item-master.create');
    }

    /**
     * Store a newly created item
     * Note: HANA write operations are not implemented yet (read-only mode)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'itmcode' => 'required|string|max:25',
            'itmnme' => 'required|string|max:100',
            'itmmod' => 'nullable|string|max:100',
            'itmamp' => 'nullable|string|max:25',
            'f_war' => 'nullable|integer',
            'pa_war' => 'nullable|integer',
            'remark' => 'nullable|string|max:250',
            'brand' => 'nullable|string|max:150',
        ]);

        // TODO: Implement HANA insert if needed
        // For now, just redirect back with a message
        return redirect()->route('item.index')
            ->with('info', 'Create functionality for HANA is read-only mode.');
    }

    /**
     * Show the form for editing the specified item
     */
    public function edit($itmcode)
    {
        $item = $this->hanaService->getItem($itmcode);
        
        if (!$item) {
            return redirect()->route('item.index')
                ->with('error', 'Item not found.');
        }

        // Convert to object for blade template
        $itemObject = (object) [
            'itmcode' => $item['ITMCODE'] ?? null,
            'itmnme' => $item['ITMNME'] ?? null,
            'itmmod' => $item['ITMMOD'] ?? null,
            'itmamp' => $item['ITMAMP'] ?? null,
            'f_war' => $item['F_WAR'] ?? null,
            'pa_war' => $item['PA_WAR'] ?? null,
            'remark' => $item['REMARK'] ?? null,
            'prphase' => $item['PRPHASE'] ?? null,
            'brand' => $item['BRAND'] ?? null,
        ];

        return view('item-master.edit', ['item' => $itemObject]);
    }

    /**
     * Update the specified item
     * Note: HANA write operations are not implemented yet (read-only mode)
     */
    public function update(Request $request, $itmcode)
    {
        $validated = $request->validate([
            'itmnme' => 'required|string|max:100',
            'itmmod' => 'nullable|string|max:100',
            'itmamp' => 'nullable|string|max:25',
            'f_war' => 'nullable|integer',
            'pa_war' => 'nullable|integer',
            'remark' => 'nullable|string|max:250',
            'brand' => 'nullable|string|max:150',
        ]);

        // TODO: Implement HANA update if needed
        return redirect()->route('item.index')
            ->with('info', 'Update functionality for HANA is read-only mode.');
    }

    /**
     * Search for items (AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->input('search', '');
        
        $items = $this->hanaService->searchItems($search, 50, 0);

        // Convert to lowercase keys for JSON response
        $result = collect($items)->map(function ($item) {
            return [
                'itmcode' => $item['ITMCODE'] ?? null,
                'itmnme' => $item['ITMNME'] ?? null,
                'itmmod' => $item['ITMMOD'] ?? null,
                'itmamp' => $item['ITMAMP'] ?? null,
                'f_war' => $item['F_WAR'] ?? null,
                'pa_war' => $item['PA_WAR'] ?? null,
                'remark' => $item['REMARK'] ?? null,
                'brand' => $item['BRAND'] ?? null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Get item details for barcode printing
     */
    public function getItemForBarcode($itmcode)
    {
        $item = $this->hanaService->getItem($itmcode);
        
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json([
            'itmcode' => $item['ITMCODE'] ?? null,
            'itmnme' => $item['ITMNME'] ?? null,
            'itmmod' => $item['ITMMOD'] ?? null,
            'itmamp' => $item['ITMAMP'] ?? null,
            'f_war' => $item['F_WAR'] ?? null,
            'pa_war' => $item['PA_WAR'] ?? null,
            'remark' => $item['REMARK'] ?? null,
            'brand' => $item['BRAND'] ?? null,
        ]);
    }
}
