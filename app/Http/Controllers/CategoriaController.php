<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ProductoResource;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Cache::remember('categorias.todas', 3600, function () {
            return CategoriaResource::collection(
                Categoria::with('productos')->get()
            )->toArray(request());
        });

        return response()->json(['data' => $categorias]);
    }

    public function store(Request $request)
    {
        $cat = Categoria::create($request->validate([
            'nombre' => 'required|string|max:255',
        ]));
        Cache::forget('categorias.todas');
        return new CategoriaResource($cat);
    }

    public function show(Categoria $categoria)
    {
        return new CategoriaResource($categoria);
    }

    public function productos(Categoria $categoria)
    {
        return ProductoResource::collection(
            $categoria->productos()->with('categoria')->get()
        );
    }

    public function update(Request $request, Categoria $categoria)
    {
        $categoria->update($request->validate([
            'nombre' => 'required|string|max:255',
        ]));
        Cache::forget('categorias.todas');
        return new CategoriaResource($categoria);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        Cache::forget('categorias.todas');
        return response()->json(['message' => 'Categoría eliminada']);
    }
}
