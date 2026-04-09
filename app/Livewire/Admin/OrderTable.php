<?php

namespace App\Livewire\Admin;

use App\Domains\Order\Models\Order;
use App\Domains\Order\Enums\OrderStatus;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void  { $this->resetPage(); }

    public function render()
    {
        $orders = Order::with('user')
            ->when($this->search, fn($q) => $q->where('reference', 'ilike', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(20);

        $statuses = OrderStatus::cases();

        return view('livewire.admin.order-table', compact('orders', 'statuses'));
    }
}
