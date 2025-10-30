<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'gallery_id',
        'path',
        'sort_order'
    ];
}
