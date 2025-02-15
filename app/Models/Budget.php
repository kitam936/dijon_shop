<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'bg_date',
        'shop_id',
        'bg_kingaku',
        'YM',
        'YW',
        'YMD',
        'Y'
    ];
}
