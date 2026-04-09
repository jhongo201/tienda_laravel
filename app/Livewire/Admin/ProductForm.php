<?php

namespace App\Livewire\Admin;

use App\Domains\Product\Models\Category;
use App\Domains\Product\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;

class ProductForm extends Component
{
    public ?int $productId = null;
    public string $name        = '';
    public string $slug        = '';
    public string $description = '';
    public string $base_price  = '';
    public int    $stock       = 0;
    public ?int   $category_id = null;
    public bool   $is_active   = true;

    protected array $rules = [
        'name'        => 'required|string|min:2|max:200',
        'slug'        => 'required|string|min:2|max:200',
        'description' => 'nullable|string',
        'base_price'  => 'required|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'category_id' => 'nullable|exists:categories,id',
        'is_active'   => 'boolean',
    ];

    public function mount(?int $productId = null): void
    {
        if ($productId) {
            $product = Product::findOrFail($productId);
            $this->productId   = $productId;
            $this->name        = $product->name;
            $this->slug        = $product->slug;
            $this->description = $product->description ?? '';
            $this->base_price  = (string) $product->base_price;
            $this->stock       = $product->stock;
            $this->category_id = $product->category_id;
            $this->is_active   = $product->is_active;
        }
    }

    public function updatedName(): void
    {
        if (!$this->productId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'base_price'  => $this->base_price,
            'stock'       => $this->stock,
            'category_id' => $this->category_id,
            'is_active'   => $this->is_active,
        ];

        if ($this->productId) {
            Product::findOrFail($this->productId)->update($data);
            session()->flash('success', 'Producto actualizado correctamente.');
        } else {
            Product::create($data);
            session()->flash('success', 'Producto creado correctamente.');
            $this->reset(['name', 'slug', 'description', 'base_price', 'stock', 'category_id']);
            $this->is_active = true;
        }
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        return view('livewire.admin.product-form', compact('categories'));
    }
}
