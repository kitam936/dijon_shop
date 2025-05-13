<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'shop_id',
        'inventory_date',
        'status_id',
        'memo'
    ];

    public function details()
    {
        return $this->hasMany(InventoryDetail::class);
    }
}
