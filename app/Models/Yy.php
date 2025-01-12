<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yy extends Model
{
    use HasFactory;

    protected $fillable = [
        'Y',
        'prev_Y',

    ];
}
