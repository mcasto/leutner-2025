<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleImportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactFailureController;
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

Route::controller(ContactFailureController::class)
    ->group(function () {
        Route::post('contact-failure', 'store');
    });

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });

Route::controller(ArticleImportController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('article-import/setup', 'setup');
        Route::post('article-import', 'import');
        Route::put('article-import/{id}', 'update');
    });
