<?php
namespace Database\Factories;

use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id'      => Category::factory(),
            'name'             => ucwords($name),
            'slug'             => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description'      => fake()->paragraph(3),
            'base_price'       => fake()->randomElement([
                29900, 49900, 79900, 99900, 129900, 159900, 199900
            ]),
            'stock'            => fake()->numberBetween(0, 50),
            'is_active'        => true,
            'meta_title'       => null,
            'meta_description' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function sinStock(): static
    {
        return $this->state(['stock' => 0]);
    }
}