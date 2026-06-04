<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\ProductoResource;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy(
                $request->get('orden', 'nombre'),
                $request->get('dir', 'asc')
            )
            ->paginate(
                $request->get('por_pagina', 6)
            );

        return ProductoResource::collection($productos);
    }

    public function store(StoreProductoRequest $request)
    {
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {

            $datos['imagen'] = $request
                ->file('imagen')
                ->store('productos', 'public');
        }

        $producto = Producto::create($datos);

        return response()->json([
            'message' => 'Producto creado correctamente',
            'data' => $producto
        ], 201);
    }

    public function show(Producto $producto)
    {
        return new ProductoResource(
            $producto->load('categoria')
        );
    }

    public function update(
        UpdateProductoRequest $request,
        Producto $producto
    ) {
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {

            if ($producto->imagen) {
                Storage::disk('public')
                    ->delete($producto->imagen);
            }

            $datos['imagen'] = $request
                ->file('imagen')
                ->store('productos', 'public');
        }

        $producto->update($datos);

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'data' => $producto
        ]);
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {

            Storage::disk('public')
                ->delete($producto->imagen);
        }

        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ]);
    }
}