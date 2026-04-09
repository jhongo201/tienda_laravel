<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExpirePendingOrders implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(): void
    {
        Order::query()
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(30))
            ->update([
                'status' => 'expired'
            ]);
    }
}