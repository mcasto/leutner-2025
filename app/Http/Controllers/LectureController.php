<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LectureController extends Controller
{
    public function index()
    {
        return Cache::remember('leutner-lectures', now()->addHour(), function () {
            return ['status' => 'ok', 'data' => Lecture::all()];
        });
    }
}
