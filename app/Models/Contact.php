<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'body',
        'hash',
    ];

    public static function hashFor(string $email, string $subject, string $body): string
    {
        return hash('sha256', mb_strtolower(trim($email))."\n".$subject."\n".$body);
    }
}
