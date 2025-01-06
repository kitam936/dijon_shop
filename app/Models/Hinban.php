<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sku;
use App\Models\Brand;
use App\Models\Unit;



class Hinban extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'unit_id',
        'brand_id',
        'year_code',
        'shohin_gun',
        'hinban_name',
        'm_price',
        'price',
        'cost',
        'hinban_info',
        'vendor_id',
        'face',
        'hinban_image'
    ];


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

}
