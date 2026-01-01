<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\PublicQueueController;

Route::get('/queue/public', [PublicQueueController::class, 'get']);

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/queues', [QueueController::class, 'list']);
        Route::post('/queue/next', [QueueController::class, 'next']);
        Route::post('/queue/prev', [QueueController::class, 'prev']);

        Route::post('/queue/issue', [QueueController::class, 'issue']);
    });
});

