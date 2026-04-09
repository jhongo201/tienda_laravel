<?php
namespace App\Http\Controllers;

use App\Domains\Product\Services\ProductService;

class ShopController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index()
    {
        return view('shop.index');
    }

    public function catalog()
    {
        return view('shop.index');
    }

    public function show(string $slug)
    {
        $product = $this->productService->findBySlug($slug);
        return view('shop.show', compact('product'));
    }

    public function category(string $slug)
    {
        return redirect()->route('catalog', ['category' => $slug]);
    }
}