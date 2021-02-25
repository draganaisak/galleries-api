<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register'])->middleware('guest:api');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest:api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware('guest:api');

Route::resource('galleries', GalleryController::class)
    ->middleware('auth:api')->except([
        'index'
    ]);
Route::get('galleries', [GalleryController::class, 'index']);
Route::get('my-galleries', [GalleryController::class, 'getMyGalleries'])->middleware('auth:api');

