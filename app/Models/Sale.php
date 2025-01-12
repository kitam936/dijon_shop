<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use App\Models\Hinban;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'sales_date',
        'shop_id',
        'sku_id',
        'pcs',
        'tanka',
        'kingaku',
        'YM',
        'YW',
        'YMD',
        'Y'
    ];


    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function hinban()
    {
        return $this->belongsTo(Hinban::class);
    }

    public function scopeYms($q)
    {
        $YWs=DB::table('sales')
        ->select(['YW','YM'])
        ->groupBy(['YW','YM'])
        ->orderBy('YM','desc')
        ->orderBy('YW','desc');
        return $q;
    }


}
