<?php
namespace Database\Seeders;

use App\Domains\Product\Models\Category;
use App\Domains\Product\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = [
            'Camisetas', 'Pantalones', 'Zapatos',
            'Accesorios', 'Vestidos', 'Deportivo',
        ];

        $categorias = collect($nombres)->map(fn($nombre) =>
            Category::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                ['name' => $nombre]
            )
        );

        $categorias->each(function ($categoria) {
            Product::factory(4)
                ->create(['category_id' => $categoria->id]);
        });

        $this->command->info('✓ Categorías y productos creados.');
    }
}