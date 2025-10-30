<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailchimpResponse extends Model
{
    protected $fillable = [
        'submitted_info',
        'response'
    ];

    protected $casts = [
        'submitted_info' => 'json',
        'response' => 'json'
    ];
}
