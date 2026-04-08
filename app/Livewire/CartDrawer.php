<?php 

namespace App\Livewire; 

use App\Domains\Cart\Services\CartService; 
use Livewire\Component; 
use Livewire\Attributes\On; 

class CartDrawer extends Component { 
    public bool $open = false; #[On('cart-updated')] 
    public function refresh(): void {} 
    public function toggle(): void { $this->open = !$this->open; } 
    public function remove(int $itemId, CartService $cartService): void { 
        $cartService->remove($itemId); 
        $this->dispatch('cart-updated'); 
    } 
    public function updateQty(int $itemId, int $qty, CartService $cartService): void { 
        $cartService->update($itemId, $qty); 
        $this->dispatch('cart-updated'); 
    } 
    public function render(CartService $cartService) { 
        $cart = $cartService->getOrCreate()->load('items.product'); 
        $total = $cartService->getTotal(); 
        $count = $cartService->getItemCount(); 
        return view('livewire.cart-drawer', compact('cart', 'total', 'count')); 
    } 
}