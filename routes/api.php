<?php

use App\Http\Controllers\API\GajiUnivController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PegawaiController;
use App\Http\Controllers\API\GolonganController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AUTH USER ADMIN
Route::name('auth.')->group(function(){
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });

});

//Pegawai
Route::prefix('pegawai')->middleware('auth:sanctum')->name('pegawai.')->group(
function(){
    Route::get('', [PegawaiController::class, 'fetch'])->name('fetch');
    Route::get('/{id}', [PegawaiController::class, 'fetch'])->name('fetch');
    Route::post('create', [PegawaiController::class, 'create'])->name('create');
    Route::post('update/{id}', [PegawaiController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [PegawaiController::class, 'destroy'])->name('delete');

});

// Golongan
Route::prefix('golongan')->middleware('auth:sanctum')->name('golongan.')->group(
    function(){
        Route::get('', [GolonganController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [GolonganController::class, 'fetch'])->name('fetch');
        Route::post('create', [GolonganController::class, 'create'])->name('create');
        Route::post('update/{id}', [GolonganController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [GolonganController::class, 'destroy'])->name('delete');

    });


