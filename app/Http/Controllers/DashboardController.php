<?php

namespace App\Http\Controllers;

use App\Domains\Order\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->withCount('items')
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total'     => $orders->count(),
            'spent'     => $orders->whereIn('status->value', ['paid', 'processing', 'shipped', 'delivered'])
                                  ->sum('total'),
            'pending'   => $orders->filter(fn($o) => $o->status->value === 'pending')->count(),
            'delivered' => $orders->filter(fn($o) => $o->status->value === 'delivered')->count(),
        ];

        // Calcular total gastado directo en DB para precisión
        $stats['spent'] = Order::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
            ->sum('total');

        $recentOrders = $orders->take(5);

        return view('dashboard', compact('user', 'stats', 'recentOrders'));
    }
}
