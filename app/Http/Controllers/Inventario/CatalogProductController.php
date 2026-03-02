<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\CatalogProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CatalogProductController extends Controller
{
    public function index()
    {
        return view('ferreteria.catalogo.index');
    }

    public function data(Request $request)
    {
        $query = CatalogProduct::query();

        if ($request->type) {
            $query->where('type_catalogo', $request->type);
        }
        

        return DataTables::of($query)->make(true);
    }

    public function filters()
    {
        return response()->json([
            'types' => CatalogProduct::select('type_catalogo')->distinct()->pluck('type_catalogo')
        ]);
    }
}
