<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoveDetail;
use App\Models\User;
use App\Models\Shop;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toShop()
    {
        return $this->belongsTo(Shop::class, 'to_shop_id');
    }


}
