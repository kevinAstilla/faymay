<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware([AuthMiddleware::class])->group(function () {

    Route::get('/article', [ArticleController::class, 'index']);
    Route::post('/article', [ArticleController::class, 'create']);
    Route::get('/article/{article}', [ArticleController::class, 'show']);
    Route::put('/article/{article}', [ArticleController::class, 'update']);
    Route::delete('/article/{article}', [ArticleController::class, 'destroy']);

    Route::get('/source', [SourceController::class, 'index']);
    Route::post('/source', [SourceController::class, 'create']);
    Route::get('/source/{source}', [SourceController::class, 'show']);
    Route::put('/source/{source}', [SourceController::class, 'update']);
    Route::delete('/source/{source}', [SourceController::class, 'destroy']);

    Route::get('/user', [UserController::class, 'profile']);
    Route::put('/user/update-preferences', [UserController::class, 'updatePreferences']);
    Route::post('/user/logout', [UserController::class, 'logout']);
});