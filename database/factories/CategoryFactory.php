<?php
namespace Database\Factories;

use App\Domains\Product\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Camisetas', 'Pantalones', 'Zapatos', 'Accesorios',
            'Vestidos', 'Chaquetas', 'Ropa Interior', 'Deportivo',
        ]);

        return [
            'name'      => $name,
            'slug'      => Str::slug($name),
            'parent_id' => null,
            'image'     => null,
        ];
    }
}