<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ym extends Model
{
    use HasFactory;

    protected $fillable = [
        'YM',
        'prev_YM',

    ];
}
