<?php

namespace App\Domains\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'product_id',
        'variant_id',
        'name_snapshot',
        'qty',
        'unit_price',
    ];
}
