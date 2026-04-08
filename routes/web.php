<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\ShopController; 
use App\Http\Controllers\CartController; 
use App\Http\Controllers\CheckoutController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\Admin\ProductController; 
use App\Http\Controllers\Admin\CategoryController; 
use App\Http\Controllers\Admin\OrderAdminController; 
use App\Http\Controllers\Admin\DashboardController; 
use App\Http\Controllers\WompiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController as CustomerDashboardController;


// ── Rutas públicas ────────────────────────────── 
Route::get('/', [HomeController::class, 'index'])->name('home'); 
Route::get('/productos', [ShopController::class, 'catalog'])->name('catalog'); 
Route::get('/productos/{slug}', [ShopController::class, 'show'])->name('product.show'); Route::get('/categoria/{slug}', [ShopController::class, 'category'])->name('category.show'); 
// ── Webhook Wompi (sin CSRF) ──────────────────── 
Route::post('/webhooks/wompi', [WompiController::class, 'webhook']) ->name('webhooks.wompi') ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ── Carrito (guest + autenticado) ─────────────── 
Route::prefix('carrito')->name('cart.')->group(function () { 
Route::get('/', [CartController::class, 'index'])->name('index'); 
Route::post('/agregar', [CartController::class, 'add'])->name('add'); 
Route::patch('/actualizar/{item}', [CartController::class, 'update'])->name('update'); 
Route::delete('/eliminar/{item}', [CartController::class, 'remove'])->name('remove'); 
}); 

// ── Rutas de cliente autenticado ──────────────── 
Route::middleware(['auth'])->group(function () { 
    // Dashboard — requerido por Breeze
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout'); 
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store'); 
    Route::get('/checkout/retorno', [CheckoutController::class, 'return'])->name('checkout.return'); 
    Route::prefix('mis-ordenes')->name('orders.')->group(function () { 
        Route::get('/', [OrderController::class, 'index'])->name('index'); 
        Route::get('/{reference}', [OrderController::class, 'show'])->name('show'); 
    }); 
});

// ── Panel Admin ───────────────────────────────── 
Route::middleware(['auth', 'role:admin']) ->prefix('admin') ->name('admin.') ->group(function () { 
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); 
    Route::resource('productos', ProductController::class); 
    Route::resource('categorias', CategoryController::class); 
    Route::prefix('ordenes')->name('orders.')->group(function () { 
        Route::get('/', [OrderAdminController::class, 'index'])->name('index'); 
        Route::get('/{order}', [OrderAdminController::class, 'show'])->name('show'); 
        Route::patch('/{order}/estado', [OrderAdminController::class, 'updateStatus'])->name('status'); 
    }); 
}); 

require __DIR__.'/auth.php';
