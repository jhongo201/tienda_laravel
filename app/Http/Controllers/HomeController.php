<?php

namespace App\Http\Controllers;

use App\Domains\Product\Models\Category;
use App\Domains\Product\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->has('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        $featured = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featured'));
    }
}
