<?php
namespace App\Domains\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductVariant;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'variant_id', 'qty', 'unit_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}