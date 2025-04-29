<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\RegisterController;
use Tmi\Framework\Http\Response;
use Tmi\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    Route::post('/posts', [PostController::class, 'store']),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::get('/register', [RegisterController::class, 'form']),
    Route::post('/register', [RegisterController::class, 'register']),

];
