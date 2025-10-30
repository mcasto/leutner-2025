<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'label',
        'credit',
        'sort_order',
        'thumbnail'
    ];
}
