<?php

namespace App\Models;

use App\Models\Scopes\Stock_subtotal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StockData extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new Stock_subtotal);
    }
}
