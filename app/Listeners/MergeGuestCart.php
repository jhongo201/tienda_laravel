<?php

namespace App\Listeners;

use App\Domains\Cart\Models\Cart; 
use Illuminate\Auth\Events\Login; 
use Illuminate\Support\Facades\Session;

class MergeGuestCart
{
    /**
     * Create the event listener.
     */
    public function handle(Login $event): void { 
        $guestCart = Cart::where('session_id', Session::getId()) ->with('items') ->first(); 
        if (!$guestCart || $guestCart->items->isEmpty()) 
            return; 
        
            $userCart = Cart::firstOrCreate(['user_id' => $event->user->id]); 
            foreach ($guestCart->items as $guestItem) { 
            $existing = $userCart->items() ->where('product_id', $guestItem->product_id) ->where('variant_id', $guestItem->variant_id) ->first(); 
            if ($existing) { 
            // Tomar la cantidad mayor 
                $existing->update(['qty' => max($existing->qty, $guestItem->qty)]); 
            } else { 
                $userCart->items()->create($guestItem->only([ 'product_id', 'variant_id', 'qty', 'unit_price' ])); 
            } 
        } 
        $guestCart->delete(); 
    } 
}

