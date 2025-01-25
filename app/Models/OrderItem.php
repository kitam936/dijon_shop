<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sku;
use App\Models\Order;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'sku_id', 'pcs'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
}
