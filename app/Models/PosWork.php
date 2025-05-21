<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'raw_cd',
        'sku_id',
        'hinban_id',
        'price',
        'pcs'
    ];
}
