<?php

namespace App\Http\Controllers;

use App\Models\ContactFailure;
use Illuminate\Http\Request;

class ContactFailureController extends Controller
{
    public function store(Request $request)
    {
        ContactFailure::create([
            'source' => 'frontend',
            'reason' => (string) $request->input('reason', 'Unknown frontend validation failure'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            'join' => $request->boolean('join'),
        ]);

        return ['status' => 'ok'];
    }
}
