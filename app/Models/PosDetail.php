<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PosHeaader;

class PosDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_header_id',
        'user_id',
        'sku_id',
        'price',
        'pcs'
    ];

    public function Header()
    {
        return $this->belongsTo(PosHeader::class);
    }
}
