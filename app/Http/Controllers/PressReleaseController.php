<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PressReleaseController extends Controller
{
    public function index()
    {
        return Cache::remember('leutner-press-releases', now()->addHour(), function () {
            $recs = PressRelease::orderBy('release_date', 'desc')
                ->get();

            return ['status' => 'ok', 'data' => $recs];
        });
    }
}
