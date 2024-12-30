<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\Hinban;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'shop_id',
        'sku_id',
        'pcs',
        'zaikogaku',

    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function hinban()
    {
        return $this->belongsTo(Hinban::class);
    }
}
