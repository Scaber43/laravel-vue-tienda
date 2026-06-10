<?php

namespace App\Http\Controllers;

use App\Events\NuevoPedidoRecibido;
use App\Events\StockBajoAlerta;
use App\Jobs\EnviarConfirmacionPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1'
        ]);

        $pedido = DB::transaction(function () use ($request) {
            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'total'   => collect($request->items)
                    ->sum(fn($i) => $i['precio'] * $i['cantidad']),
                'estado'  => 'pendiente'
            ]);

            foreach ($request->items as $item) {
                $pedido->items()->create([
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio']
                ]);

                $producto = Producto::find($item['producto_id']);
                if ($producto) {
                    $producto->decrement('stock', $item['cantidad']);
                    $fresco = $producto->fresh();
                    if ($fresco->stock <= 5) {
                        broadcast(new StockBajoAlerta($fresco, $fresco->stock));
                    }
                }
            }

            return $pedido;
        });

        broadcast(new NuevoPedidoRecibido($pedido));

        EnviarConfirmacionPedido::dispatch($pedido)
            ->delay(now()->addSeconds(5));

        return response()->json(['pedido_id' => $pedido->id], 201);
    }

    public function show(Pedido $pedido)
    {
        return response()->json($pedido);
    }
}
