<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hinban;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'season_id',
        'season_name',

    ];


    public function hinban()
    {
        return $this->hasMany(Hinban::class);
    }
}
