<?php
namespace App\Domains\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'attributes',
        'price_modifier', 'stock', 'is_active',
    ];

    protected $casts = [
        'attributes'     => 'array',
        'price_modifier' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\ProductVariantFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}