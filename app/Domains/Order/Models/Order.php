<?php

namespace App\Domains\Order\Models;

use App\Domains\Order\Enums\OrderStatus;
use App\Domains\Order\Events\OrderPaid;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'reference',
        'user_id',
        'coupon_id',
        'address_snapshot',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'total',
        'status',
    ];

    protected $casts = [
        'address_snapshot' => 'array',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Domains\Payment\Models\Payment::class);
    }

    public function canTransitionTo(OrderStatus $status): bool
    {
        return in_array($status, $this->status->allowedTransitions());
    }

    public function markAsPaid(): void
    {
        DB::transaction(function () {

            // ⚠️ Evitar doble ejecución (idempotencia básica)
            if ($this->status === OrderStatus::Paid) {
                return;
            }

            // ⚠️ Validar transición
            if (! $this->canTransitionTo(OrderStatus::Paid)) {
                throw new \Exception('Invalid state transition to Paid');
            }

            // ✅ Actualizar estado
            $this->update([
                'status' => OrderStatus::Paid
            ]);

            // ✅ Disparar evento
            OrderPaid::dispatch($this);
        });
    }
}