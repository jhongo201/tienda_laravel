<?php

namespace App\Http\Controllers;

use App\Domains\Order\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(string $reference)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('reference', $reference)
            ->with('items')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
