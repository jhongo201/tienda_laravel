<?php 

// Este archivo está mal ubicado. La clase real está en App\Domains\Order\Services\OrderService.
// @deprecated — No usar este archivo.
namespace App\Domains\Product\Services\Deprecated; 

use App\Domains\Cart\Models\Cart; 
use App\Domains\Order\Models\Order; 
use App\Domains\Order\Enums\OrderStatus; 
use App\Domains\Coupon\Models\Coupon; 
use Illuminate\Support\Str; 

class OrderService { 

	public function createFromCart(Cart $cart, array $addressData, ?Coupon $coupon = null): Order { 
		$items = $cart->items()->with('product')->get(); 
		$subtotal = $items->sum(fn($i) => $i->unit_price * $i->qty); 
		$discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0; 
		$shipping = $this->calculateShipping($addressData['city'] ?? ''); 
		$total = $subtotal - $discount + $shipping; 
		$order = Order::create([ 
			'reference' => 'ORD-' . strtoupper(Str::ulid()), 
			'user_id' => $cart->user_id, 
			'coupon_id' => $coupon?->id, 
			'address_snapshot'=> $addressData, 
			'subtotal' => $subtotal, 
			'discount_amount' => $discount, 
			'shipping_amount' => $shipping, 
			'total' => $total, 
			'status' => OrderStatus::Pending, 
			]); 
		foreach ($items as $item) { 
			$order->items()->create([ 
				'product_id' => $item->product_id, 
				'variant_id' => $item->variant_id, 
				'name_snapshot' => $item->product->name, 
				'qty' => $item->qty, 
				'unit_price' => $item->unit_price, 
			]); 
		} 
		return $order; 
	} 

	private function calculateShipping(string $city): float { 
	// Lógica básica — implementar con ShippingZone 
		return 15000.00; 
	} 
}