<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TenagaKerjaController;
use App\Http\Controllers\Api\PendudukUmurApiController;
use App\Http\Controllers\Api\PendudukApiController;
use App\Http\Controllers\Api\PendudukJatengApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tenaga-kerja', [TenagaKerjaController::class, 'index']);

Route::prefix('penduduk-umur')->group(function () {
    Route::get('/', [PendudukUmurApiController::class, 'index']);
    Route::post('/', [PendudukUmurApiController::class, 'store']);
    Route::delete('/{id}', [PendudukUmurApiController::class, 'destroy']);
});

Route::prefix('penduduk')->group(function () {
    Route::get('/', [PendudukApiController::class, 'index']);
    Route::post('/', [PendudukApiController::class, 'store']);
    Route::delete('/{id}', [PendudukApiController::class, 'destroy']);
});

Route::prefix('penduduk-jateng')->group(function () {
    Route::get('/', [PendudukJatengApiController::class, 'index']);
    Route::post('/', [PendudukJatengApiController::class, 'store']);
    Route::delete('/{id}', [PendudukJatengApiController::class, 'destroy']);
});