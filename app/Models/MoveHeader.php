<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoveDetail;

class MoveHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'from_shop_id',
        'to_shop_id',
        'user_id',
        'move_date',
        'status_id',
        'memo'
    ];

    public function details()
    {
        return $this->hasMany(MoveDetail::class);
    }
}
