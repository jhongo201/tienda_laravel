<?php
namespace App\Domains\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "category_id",
        "name",
        "slug",
        "description",
        "base_price",
        "stock",
        "is_active",
        "meta_title",
        "meta_description",
    ];

    protected $casts = ["base_price" => "decimal:2", "is_active" => "boolean"];
    
    protected static function newFactory()
    {
        return \Database\Factories\ProductFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function scopeActive($query)
    {
        return $query->where("is_active", true);
    }
    public function getEffectivePrice(?int $variantId = null): float
    {
        if ($variantId) {
            $variant = $this->variants->find($variantId);
            if ($variant) {
                return $this->base_price + $variant->price_modifier;
            }
        }
        return $this->base_price;
    }
}
