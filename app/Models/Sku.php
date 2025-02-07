<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hinban;
use App\Models\Col;
use App\Models\Size;

class Sku extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'hinban_id',
        'col_id',
        'size_id',
        'sku_image',
    ];

    public function hinban()
    {
        return $this->belongsTo(Hinban::class);
    }

    public function col()
    {
        return $this->belongsTo(Col::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
