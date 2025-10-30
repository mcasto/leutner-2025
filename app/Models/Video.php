<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'label',
        'folder',
        'embed',
        'credit',
        'event',
        'sort_order',
        'thumbnail'
    ];
}
