<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'status_name',
    ];
}
