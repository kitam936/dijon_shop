<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yw extends Model
{
    use HasFactory;

    protected $fillable = [
        'YW',
        'prev_YW',

    ];
}
