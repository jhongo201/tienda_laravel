<?php namespace App\Domains\Coupon\Services;
use App\Domains\Coupon\Models\Coupon;
class CouponValidator
{
    public function validate(string $code, float $cartTotal): Coupon
    {
        $coupon = Coupon::where("code", strtoupper($code))->first();
        if (!$coupon) {
            throw new \Exception("Cupón no encontrado.");
        }
        if (!$coupon->isValid($cartTotal)) {
            throw new \Exception("El cupón no es válido o ha expirado.");
        }
        return $coupon;
    }
    public function apply(Coupon $coupon): void
    {
        if ($coupon->uses_left !== null) {
            $coupon->decrement("uses_left");
        }
    }
}
