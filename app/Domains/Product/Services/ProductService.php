<?php 
namespace App\Domains\Product\Services; 

use App\Domains\Product\Models\Product; 
use App\Domains\Product\Models\Category; 
use Illuminate\Pagination\LengthAwarePaginator; 
class ProductService { 
	public function getForCatalog(array $filters = []): LengthAwarePaginator { 
		return Product::with(['category', 'variants']) ->active() ->when(
			$filters['category'] ?? null, 
				fn($q, $cat) => $q->whereHas('category', fn($q) => $q->where('slug', $cat)) 
		) ->when($filters['search'] ?? null, fn($q, $s) => $q->where('name', 'ilike', "%{$s}%") ) 
		->when($filters['min_price'] ?? null, fn($q, $p) => $q->where('base_price', '>=', $p) ) 
		->when($filters['max_price'] ?? null, fn($q, $p) => $q->where('base_price', '<=', $p) ) 
		->orderBy($filters['order_by'] ?? 'created_at', $filters['order'] ?? 'desc') ->paginate(12); 
	} 
	public function findBySlug(string $slug): Product { 
		return Product::with(['category', 'variants']) ->active() ->where('slug', $slug) ->firstOrFail(); 
	} 
	public function getAllCategories(): \Illuminate\Database\Eloquent\Collection { 
		return Category::withCount('products')->get(); 
	} 
}