<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use Illuminate\Http\Request;

class PressReleaseController extends Controller
{
    public function index()
    {
        $recs = PressRelease::orderBy('release_date', 'desc')
            ->get();

        return ['status' => 'ok', 'data' => $recs];
    }
}
