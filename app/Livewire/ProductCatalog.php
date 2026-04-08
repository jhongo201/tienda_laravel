<?php 

namespace App\Livewire; 

use App\Domains\Product\Services\ProductService; 
use Livewire\Component; use Livewire\WithPagination; 

class ProductCatalog extends Component { 
    use WithPagination; public string $search = ''; 
    public string $category = ''; 
    public string $orderBy = 'created_at'; 
    public ?float $minPrice = null; 
    public ?float $maxPrice = null; 
    protected $queryString = ['search', 'category', 'orderBy']; 
    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedCategory(): void { $this->resetPage(); }
    public function updatedMinPrice(): void { $this->resetPage(); }
    public function updatedMaxPrice(): void { $this->resetPage(); }
    public function updatedOrderBy(): void { $this->resetPage(); } 
    public function render(ProductService $service) {
        $products = $service->getForCatalog([ 
            'search' => $this->search, 'category' => $this->category, 
            'order_by' => $this->orderBy, 'min_price' => $this->minPrice, 
            'max_price' => $this->maxPrice, 
        ]);
        $categories = $service->getAllCategories();
        return view('livewire.product-catalog', compact('products', 'categories')); 
    } 
}