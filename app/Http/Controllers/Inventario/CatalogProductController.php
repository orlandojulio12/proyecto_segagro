<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\CatalogProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CatalogProductController extends Controller
{
    // Vista principal (solo HTML)
    public function index()
    {
        return view('ferreteria.catalogo.index');
    }

    // Endpoint para DataTables Server-Side
    public function data()
{
    $query = CatalogProduct::select([
        'id',
        'sku',
        'consecutive',
        'element_description',
        'family_name',
        'class_name',
        'segment_description',
    ]);

    return DataTables::of($query)->make(true);
}
}
