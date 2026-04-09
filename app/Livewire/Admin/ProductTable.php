<?php

namespace App\Livewire\Admin;

use App\Domains\Product\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showInactive = false;

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedShowInactive(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Producto eliminado.');
    }

    public function restore(int $id): void
    {
        Product::withTrashed()->findOrFail($id)->restore();
        session()->flash('success', 'Producto restaurado.');
    }

    public function toggleActive(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
    }

    public function render()
    {
        $products = Product::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
            ->when(!$this->showInactive, fn($q) => $q->where('is_active', true))
            ->withTrashed()
            ->latest()
            ->paginate(15);

        return view('livewire.admin.product-table', compact('products'));
    }
}
