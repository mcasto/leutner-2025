<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    protected $fillable = [
        '_id',
        'label',
        'path',
        'parent',
        'visible',
        'sort_order'
    ];
}
