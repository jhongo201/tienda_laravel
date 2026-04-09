<?php 

namespace App\Domains\Product\Services; 

use App\Domains\Product\Models\Product; 
use Illuminate\Support\Facades\DB; 

class StockService { 
	/** * Reservar stock de forma atómica. * Debe llamarse DENTRO de una transacción DB. * 
	* @param array $items [['id' => int, 'qty' => int], ...] 
	* @throws \Exception si no hay stock suficiente */ 
	
	public function reserve(array $items): void { DB::transaction(function () use ($items) { 
		foreach ($items as $item) { 
			$product = Product::lockForUpdate()->findOrFail($item['id']); 
			if ($product->stock < $item['qty']) { 
				throw new \Exception( "Stock insuficiente para: {$product->name}. Disponible: {$product->stock}" ); 
			} $product->decrement('stock', $item['qty']); 
		} 
	}); 


	public function restore(array $items): void { 
		foreach ($items as $item) { 
			Product::find($item['id'])?->increment('stock', $item['qty']); 
		} 
	} 
}