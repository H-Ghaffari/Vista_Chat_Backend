<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
     Route::post('/me', [AuthController::class, 'me']);
});

Route::Post('/register', [AuthController::class, 'register']);
Route::Post('/login', [AuthController::class, 'login']);