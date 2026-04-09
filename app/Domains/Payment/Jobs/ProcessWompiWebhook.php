<?php

namespace App\Domains\Payment\Jobs;

use App\Domains\Order\Models\Order;
use App\Domains\Payment\Models\Payment;
use App\Domains\Product\Services\StockService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWompiWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    public int $tries = 3;
    public function __construct(private array $payload)
    {
    }
    public function handle(StockService $stockService): void
    {
        $transaction = $this->payload["data"]["transaction"] ?? [];
        $transactionId = $transaction["id"] ?? null;
        $reference = $transaction["reference"] ?? null;
        $status = $transaction["status"] ?? null;
        if (!$transactionId || !$reference) {
            return;
        }

        // Guard 1: ¿Ya procesamos esta transacción?
        if (Payment::where("transaction_id", $transactionId)->exists()) {
            Log::info("Webhook duplicado ignorado", ["txn" => $transactionId]);
            return;
        }

        if ($status !== "APPROVED") {
            return;
        }

        $order = Order::where("reference", $reference)->firstOrFail();

        // Guard 2: ¿La orden ya está pagada?
        if ($order->status->value === "paid") {
            return;
        }
        DB::transaction(function () use (
            $order,
            $transaction,
            $transactionId,
            $stockService
        ) {
            // UNIQUE constraint es la última línea de defensa
            Payment::create([
                "order_id" => $order->id,
                "gateway" => "wompi",
                "transaction_id" => $transactionId,
                "amount" => $transaction["amount_in_cents"] / 100,
                "currency" => $transaction["currency"] ?? "COP",
                "status" => "approved",
                "payload" => $transaction,
                "processed_at" => now(),
            ]);

            // Reservar stock atómicamente
            $items = $order->items
                ->map(fn($i) => ["id" => $i->product_id, "qty" => $i->qty])
                ->toArray();
            $stockService->reserve($items);

            // Marcar orden pagada (dispara evento → email)
            $order->markAsPaid();
            // Vaciar carrito
            $order->user?->cart?->delete();
        });
    }
}
