<?php namespace App\Http\Controllers\Admin;
use App\Domains\Order\Models\Order;
use App\Domains\Order\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with("user")
            ->latest()
            ->paginate(20);
        return view("admin.orders.index", compact("orders"));
    }
    public function show(Order $order)
    {
        $order->load("items.product", "payments", "user");
        return view("admin.orders.show", compact("order"));
    }
    public function updateStatus(Request $request, Order $order)
    {
        $newStatus = OrderStatus::from($request->status);
        if (!$order->canTransitionTo($newStatus)) {
            return back()->withErrors([
                "status" => "No se puede pasar de {$order->status->label()} a {$newStatus->label()}",
            ]);
        }
        $order->update(["status" => $newStatus]);
        return back()->with(
            "success",
            "Orden actualizada a: {$newStatus->label()}"
        );
    }
}
