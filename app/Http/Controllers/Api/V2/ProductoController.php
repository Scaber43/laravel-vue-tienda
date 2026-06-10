<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\ProductoController as V1ProductoController;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends V1ProductoController
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->q) {
            $query->whereFullText(['nombre', 'descripcion'], $request->q);
        }

        return ProductoResource::collection($query->paginate(20));
    }
}
