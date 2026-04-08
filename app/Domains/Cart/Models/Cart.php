<?php
namespace App\Domains\Cart\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'email',
        'converted_at', 'expires_at',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getTotal(): float
    {
        return $this->items->sum(fn($item) => $item->unit_price * $item->qty);
    }
}