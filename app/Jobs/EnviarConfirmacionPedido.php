<?php

namespace App\Jobs;

use Throwable;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\ConfirmacionPedidoNotification;

class EnviarConfirmacionPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(public Pedido $pedido)
    {
    }

    public function handle(): void
    {
        $this->pedido->user->notify(
            new ConfirmacionPedidoNotification($this->pedido)
        );

        $this->pedido->update([
            'email_enviado_at' => now()->toDateTimeString()
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error(
            'Fallo envío email pedido ' .
            $this->pedido->id .
            ' - ' .
            $e->getMessage()
        );
    }
}
