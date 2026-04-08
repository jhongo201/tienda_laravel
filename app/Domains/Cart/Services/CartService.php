<?php 

namespace App\Domains\Cart\Services; 

use App\Domains\Cart\Models\Cart; 
use App\Domains\Cart\Models\CartItem; 
use App\Domains\Product\Models\Product; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Session; 

class CartService { public function getOrCreate(): Cart { 

	if (Auth::check()) { 
		return Cart::firstOrCreate(['user_id' => Auth::id()]); 
	} 
	return Cart::firstOrCreate(['session_id' => Session::getId()]); 
} 

public function add(int $productId, int $qty = 1, ?int $variantId = null): void { 
	$product = Product::findOrFail($productId); $cart = $this->getOrCreate(); 
	$price = $product->getEffectivePrice($variantId); 
	$item = $cart->items() ->where('product_id', $productId) ->where('variant_id', $variantId) ->first(); 
	if ($item) { 
		$item->increment('qty', $qty); 
	} else { 
		$cart->items()->create([ 'product_id' => $productId, 'variant_id' => $variantId, 'qty' => $qty, 'unit_price' => $price, ]); 
	} 
} 

public function update(int $itemId, int $qty): void { $cart = $this->getOrCreate(); 
	$item = $cart->items()->findOrFail($itemId); if ($qty <= 0) { 
		$item->delete(); 
	} else { 
		$item->update(['qty' => $qty]); 
	} 
} 

public function remove(int $itemId): void { 
	$cart = $this->getOrCreate(); $cart->items()->findOrFail($itemId)->delete(); 
} 

public function clear(Cart $cart): void { 
	$cart->items()->delete(); $cart->delete(); 
}

public function getTotal(): float { 
	return $this->getOrCreate()->getTotal(); 
} 

public function getItemCount(): int { 
	return $this->getOrCreate()->items->sum('qty'); 
} 
}