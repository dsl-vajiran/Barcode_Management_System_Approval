<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ybgrn;
use Illuminate\Support\Facades\DB;

class HanaController extends Controller
{
    /**
     * Example using the Eloquent model (uses protected $connection = 'hana').
     */
    public function modelExample()
    {
        // simple fetch via model
        $rows = Ybgrn::limit(50)->get();
        return response()->json($rows);
    }

    /**
     * Example using the query builder with explicit HANA connection.
     */
    public function queryBuilderExample()
    {
        $rows = DB::connection('hana')
            ->table('YBGRN')
            ->select('*')
            ->limit(50)
            ->get();

        return response()->json($rows);
    }
}
