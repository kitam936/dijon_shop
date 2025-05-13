<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_header_id',
        'user_id',
        'sku_id',
        'pcs'
    ];

    public function Header()
    {
        return $this->belongsTo(InventoryHeader::class);
    }
}
