<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ymd extends Model
{
    use HasFactory;

    protected $fillable = [
        'YMD',
        'prev_YMD',

    ];
}
