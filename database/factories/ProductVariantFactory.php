<?php
namespace Database\Factories;

use App\Domains\Product\Models\ProductVariant;
use App\Domains\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $talla = fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']);
        $color = fake()->randomElement(['Negro', 'Blanco', 'Azul', 'Rojo', 'Verde']);

        return [
            'product_id'     => Product::factory(),
            'sku'            => strtoupper(fake()->unique()->bothify('SKU-####-??')),
            'attributes'     => ['talla' => $talla, 'color' => $color],
            'price_modifier' => fake()->randomElement([0, 5000, 10000, 15000]),
            'stock'          => fake()->numberBetween(0, 20),
            'is_active'      => true,
        ];
    }
}