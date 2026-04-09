<?php 

namespace App\Listeners; 

use App\Domains\Order\Events\OrderPaid; 
use App\Mail\OrderConfirmedMail; 
use Illuminate\Support\Facades\Mail; 

class SendOrderConfirmation { 

    public function handle(OrderPaid $event): void { 
        $order = $event->order->load('items.product', 'user'); 

        if ($order->user?->email) { 
            Mail::to($order->user->email) ->queue(new OrderConfirmedMail($order)); 
        } 
    } 
}