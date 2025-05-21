<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PosDetail;

class PosHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'shop_id',
        'user_id',
        'pos_date',
        'status_id',
        'memo'
    ];

    public function details()
    {
        return $this->hasMany(PosDetail::class);
    }
}
