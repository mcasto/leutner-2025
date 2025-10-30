<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $reviewList = Storage::disk('local')
            ->files("reviews/{$slug}");

        $reviews = collect($reviewList)
            ->map(function ($file) use ($slug) {
                $filename = basename($file);
                return Storage::disk('local')
                    ->get("reviews/{$slug}/{$filename}");
            });

        return ['status' => 'ok', 'data' => $reviews];
    }
}
