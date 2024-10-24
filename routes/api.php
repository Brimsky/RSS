<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/users', function() {
        return response()->json(
            \App\Models\User::where('id', '!=', auth()->id())->get()
        );
    });

    Route::get('/messages', [MessageController::class, 'index']);  
    Route::post('/messages', [MessageController::class, 'store']);
});

