<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoveHeaader;

class MoveDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'move_header_id',
        'user_id',
        'sku_id',
        'price',
        'pcs'
    ];

    public function Header()
    {
        return $this->belongsTo(MoveHeader::class);
    }
}
