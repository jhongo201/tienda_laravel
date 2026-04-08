<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Order\Models\Order;
use App\Domains\Order\Enums\OrderStatus;
use App\Domains\Product\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔹 Métricas principales
        $metrics = [
            "total_ventas" => Order::where("status", OrderStatus::Paid)->sum("total"),

            // ⚠️ corregido: solo pagadas hoy
            "ordenes_hoy" => Order::where("status", OrderStatus::Paid)
                ->whereDate("created_at", today())
                ->count(),

            "ordenes_pending" => Order::where("status", OrderStatus::Pending)->count(),

            "total_productos" => Product::active()->count(),

            "total_clientes" => User::role("cliente")->count(),
        ];

        // 🔹 Órdenes recientes
        $ordenes_recientes = Order::with("user")
            ->latest()
            ->take(10)
            ->get();

        // 🔹 Ventas por día (últimos 7 días)
        $ventas_por_dia = Order::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(total) as total')
            )
            ->where("status", OrderStatus::Paid)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->take(7)
            ->get();

        // 🔹 Top productos
        $top_productos = DB::table('order_items')
        ->select('product_id', DB::raw('SUM(qty) as total'))
        ->groupBy('product_id')
        ->orderByDesc('total')
        ->take(5)
        ->get();

        return view("admin.dashboard", compact(
            "metrics",
            "ordenes_recientes",
            "ventas_por_dia",
            "top_productos"
        ));
    }
}