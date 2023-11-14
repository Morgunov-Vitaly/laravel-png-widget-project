<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Users\IndexController;
use App\Http\Controllers\Api\V1\Widgets\ShowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->as('widgets:')->group(function () {
    Route::get('/', IndexController::class)->name('index'); // route('api:v1:users:index')
    Route::get('/{id}/widget', ShowController::class)->name('showWidget'); // route('api:v1:users/{id}/widget')
});
