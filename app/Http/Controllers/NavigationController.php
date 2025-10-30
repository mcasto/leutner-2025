<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use App\Services\NavigationTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NavigationController extends Controller
{
    public function index(NavigationTools $navTools)
    {
        return Cache::remember('leutner-nav', now()->addHour(), function () use ($navTools) {
            $paths = Navigation::where('visible', true)
                ->orderBy('sort_order')
                ->get()
                ->map(function ($item) {
                    if ($item->parent == 'NULL') {
                        $item->parent = null;
                    }
                    return $item;
                });

            return ['status' => 'ok', 'data' =>  $navTools->buildNavigationTree($paths)];
        });
    }
}
