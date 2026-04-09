<?php namespace App\Http\Controllers;
use App\Domains\Cart\Services\CartService;
use App\Domains\Order\Services\OrderService;
use App\Domains\Payment\Services\WompiService;
use Illuminate\Http\Request;
class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private WompiService $wompiService
    ) {
    }
    public function index()
    {
        $cart = $this->cartService->getOrCreate()->load("items.product");
        if ($cart->items->isEmpty()) {
            return redirect()->route("cart.index");
        }
        return view("checkout.index", compact("cart"));
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "city" => "required|string",
            "address" => "required|string",
            "phone" => "required|string",
        ]);
        $cart = $this->cartService->getOrCreate()->load("items.product");
        $order = $this->orderService->createFromCart(
            $cart,
            $request->only(["name", "city", "address", "zip", "phone"])
        );
        $wompiUrl = $this->wompiService->buildCheckoutUrl($order);
        return redirect()->away($wompiUrl);
    }
    public function return(Request $request)
    {
        $transactionId = $request->query('id');
        $reference     = $request->query('reference') ?? $transactionId;
        $status        = 'PENDING';

        if ($transactionId) {
            try {
                $status = $this->wompiService->getTransactionStatus($transactionId);
            } catch (\Throwable $e) {
                $status = 'ERROR';
            }
        }

        return view('checkout.return', compact('reference', 'status'));
    }
}
