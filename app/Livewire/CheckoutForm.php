<?php

namespace App\Livewire;

use App\Domains\Cart\Services\CartService;
use App\Domains\Coupon\Services\CouponValidator;
use App\Domains\Order\Services\OrderService;
use App\Domains\Payment\Services\WompiService;
use Livewire\Component;

class CheckoutForm extends Component
{
    public string $name    = '';
    public string $city    = '';
    public string $address = '';
    public string $zip     = '';
    public string $phone   = '';

    public string $couponCode   = '';
    public ?string $couponMessage = null;
    public bool $couponValid    = false;
    public float $discountAmount = 0;

    protected array $rules = [
        'name'    => 'required|string|min:3',
        'city'    => 'required|string|min:2',
        'address' => 'required|string|min:5',
        'phone'   => 'required|string|min:7',
        'zip'     => 'nullable|string',
    ];

    protected array $messages = [
        'name.required'    => 'El nombre es obligatorio.',
        'city.required'    => 'La ciudad es obligatoria.',
        'address.required' => 'La dirección es obligatoria.',
        'phone.required'   => 'El teléfono es obligatorio.',
    ];

    public function applyCoupon(CartService $cartService, CouponValidator $validator): void
    {
        $this->couponValid   = false;
        $this->couponMessage = null;
        $this->discountAmount = 0;

        if (empty(trim($this->couponCode))) {
            return;
        }

        $total = $cartService->getTotal();

        try {
            $coupon = $validator->validate(strtoupper(trim($this->couponCode)), $total);
            $this->discountAmount = $coupon->calculateDiscount($total);
            $this->couponValid    = true;
            $this->couponMessage  = '✓ Cupón aplicado: -$' . number_format($this->discountAmount, 0, ',', '.');
        } catch (\Exception $e) {
            $this->couponMessage = $e->getMessage();
        }
    }

    public function removeCoupon(): void
    {
        $this->couponCode     = '';
        $this->couponMessage  = null;
        $this->couponValid    = false;
        $this->discountAmount = 0;
    }

    public function submit(
        CartService $cartService,
        OrderService $orderService,
        WompiService $wompiService,
        CouponValidator $validator
    ) {
        $this->validate();

        $cart = $cartService->getOrCreate()->load('items.product');

        if ($cart->items->isEmpty()) {
            return $this->redirect(route('cart.index'));
        }

        $coupon = null;
        if ($this->couponValid && $this->couponCode) {
            try {
                $coupon = $validator->validate(strtoupper(trim($this->couponCode)), $cartService->getTotal());
                $validator->apply($coupon);
            } catch (\Exception $e) {
                $this->couponValid   = false;
                $this->couponMessage = $e->getMessage();
                return;
            }
        }

        $order = $orderService->createFromCart(
            $cart,
            [
                'name'    => $this->name,
                'city'    => $this->city,
                'address' => $this->address,
                'zip'     => $this->zip,
                'phone'   => $this->phone,
            ],
            $coupon
        );

        $wompiUrl = $wompiService->buildCheckoutUrl($order);

        $cartService->clear($cart);

        return $this->redirect($wompiUrl, navigate: false);
    }

    public function render(CartService $cartService)
    {
        $cart     = $cartService->getOrCreate()->load('items.product');
        $subtotal = $cartService->getTotal();
        $shipping = 15000.00;
        $total    = $subtotal - $this->discountAmount + $shipping;

        return view('livewire.checkout-form', compact('cart', 'subtotal', 'shipping', 'total'));
    }
}
