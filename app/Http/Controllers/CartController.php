<?php
namespace App\Http\Controllers;

use App\Domains\Cart\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        $cart  = $this->cartService->getOrCreate()->load('items.product');
        $total = $this->cartService->getTotal();
        $count = $this->cartService->getItemCount();

        return view('cart.index', compact('cart', 'total', 'count'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'required|integer|min:1|max:100',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $this->cartService->add(
            $request->product_id,
            $request->qty,
            $request->variant_id
        );

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, int $item)
    {
        $request->validate([
            'qty' => 'required|integer|min:0',
        ]);

        $this->cartService->update($item, $request->qty);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function remove(int $item)
    {
        $this->cartService->remove($item);

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}