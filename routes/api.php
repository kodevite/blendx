<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlendxController;

//Route::get('/{route}', [\App\Http\Controllers\UserController::class, 'index']);
Route::get('/{route}', [BlendxController::class, 'index']);
Route::get('/{route}/{id}', [BlendxController::class, 'show']);
Route::post('/user/store', [BlendxController::class, 'store']);
