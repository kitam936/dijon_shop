<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'co_name',
        'co_info',
    ];


    public function shop()
    {
        return $this->hasMany(Shop::class);
    }

}
