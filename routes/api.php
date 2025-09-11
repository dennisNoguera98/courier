<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/loginApp', [App\Http\Controllers\AuthController::class, 'loginApp']);

Route::get('/sync/gestor', [App\Http\Controllers\SyncController::class, 'getGestorPendingRecords']);
Route::get('/sync/courier/{userId}', [App\Http\Controllers\SyncController::class, 'getCourierAssignments']);
Route::post('/sync/upload/entregas', [App\Http\Controllers\SyncController::class, 'uploadEntregas']);
Route::post('/sync/upload/extractos', [App\Http\Controllers\SyncController::class, 'uploadExtractos']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
