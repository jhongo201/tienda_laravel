<?php

namespace App\Livewire\Admin;

use App\Domains\Product\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class CategoryForm extends Component
{
    public string $name = '';
    public string $slug = '';
    public ?int $editingId = null;

    protected array $rules = [
        'name' => 'required|string|min:2|max:100',
        'slug' => 'required|string|min:2|max:100',
    ];

    public function updatedName(): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('success', 'Categoría actualizada.');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('success', 'Categoría creada.');
        }

        $this->reset(['name', 'slug', 'editingId']);
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name      = $category->name;
        $this->slug      = $category->slug;
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', 'Categoría eliminada.');
    }

    public function cancelEdit(): void
    {
        $this->reset(['name', 'slug', 'editingId']);
    }

    public function render()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('livewire.admin.category-form', compact('categories'));
    }
}
