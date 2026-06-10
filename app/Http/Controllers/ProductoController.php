<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductoResource;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use OpenApi\Attributes as OA;

class ProductoController extends Controller
{
    #[OA\Get(
        path: '/api/v1/productos',
        tags: ['Productos'],
        summary: 'Listar productos con filtros y paginación',
        parameters: [
            new OA\Parameter(name: 'busqueda', in: 'query', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'categoria_id', in: 'query', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'precio_min', in: 'query', schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'precio_max', in: 'query', schema: new OA\Schema(type: 'number')),
            new OA\Parameter(name: 'por_pagina', in: 'query', schema: new OA\Schema(type: 'integer', example: 6)),
        ],
        responses: [new OA\Response(response: 200, description: 'Lista de productos paginada')]
    )]
    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($request->get('orden', 'nombre'), $request->get('dir', 'asc'))
            ->paginate($request->get('por_pagina', 6));

        return ProductoResource::collection($productos);
    }

    #[OA\Post(
        path: '/api/v1/productos',
        tags: ['Productos'],
        summary: 'Crear producto',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['nombre', 'precio', 'stock', 'categoria_id'],
                    properties: [
                        new OA\Property(property: 'nombre', type: 'string', example: 'Camiseta'),
                        new OA\Property(property: 'precio', type: 'number', example: 150.00),
                        new OA\Property(property: 'stock', type: 'integer', example: 10),
                        new OA\Property(property: 'categoria_id', type: 'integer', example: 1),
                        new OA\Property(property: 'imagen', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Producto creado'),
            new OA\Response(response: 403, description: 'Sin permisos'),
            new OA\Response(response: 422, description: 'Datos inválidos'),
        ]
    )]
    public function store(StoreProductoRequest $request)
    {
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($datos);

        return response()->json(['message' => 'Producto creado correctamente', 'data' => $producto], 201);
    }

    #[OA\Get(
        path: '/api/v1/productos/{id}',
        tags: ['Productos'],
        summary: 'Obtener un producto',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Producto encontrado'),
            new OA\Response(response: 404, description: 'No encontrado'),
        ]
    )]
    public function show(Producto $producto)
    {
        return new ProductoResource($producto->load('categoria'));
    }

    #[OA\Put(
        path: '/api/v1/productos/{id}',
        tags: ['Productos'],
        summary: 'Actualizar producto',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'precio', type: 'number'),
                    new OA\Property(property: 'stock', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Producto actualizado'),
            new OA\Response(response: 403, description: 'Sin permisos'),
            new OA\Response(response: 404, description: 'No encontrado'),
            new OA\Response(response: 422, description: 'Datos inválidos'),
        ]
    )]
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($datos);

        return response()->json(['message' => 'Producto actualizado correctamente', 'data' => $producto]);
    }

    #[OA\Delete(
        path: '/api/v1/productos/{id}',
        tags: ['Productos'],
        summary: 'Eliminar producto',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Producto eliminado'),
            new OA\Response(response: 403, description: 'Sin permisos'),
            new OA\Response(response: 404, description: 'No encontrado'),
        ]
    )]
    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
