<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(ArticleController::class)
    ->group(function () {
        Route::get('get-article/{slug}', 'show');
        Route::get('get-articles', 'index');
    });


Route::controller(LectureController::class)
    ->group(function () {
        Route::get('get-lectures', 'index');
    });

Route::controller(NavigationController::class)
    ->group(function () {
        Route::get('get-navigation', 'index');
    });

Route::controller(PressReleaseController::class)
    ->group(function () {
        Route::get('get-press-releases', 'index');
    });

Route::controller(ReviewController::class)
    ->group(function () {
        Route::get('get-reviews/{slug}', 'index');
    });

Route::controller(ContactController::class)
    ->group(function () {
        Route::post('send-contact', 'store');
    });
