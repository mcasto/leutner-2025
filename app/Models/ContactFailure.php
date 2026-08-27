<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFailure extends Model
{
    protected $fillable = [
        'source',
        'reason',
        'name',
        'email',
        'subject',
        'body',
    ];
}
