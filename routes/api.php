<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DosenLBController;
use App\Http\Controllers\API\KaryawanController;
use App\Http\Controllers\API\DosenTetapController;

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

//Dosen Luar Biasa
Route::prefix('dosen_luarbiasa')->middleware('auth:sanctum')->name('dosen_luarbiasa.')->group(
function(){
    Route::get('', [DosenLBController::class, 'fetch'])->name('fetch');
    Route::post('create', [DosenLBController::class, 'create'])->name('create');
    Route::put('update/{id}', [DosenLBController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [DosenLBController::class, 'destroy'])->name('delete');

});

// Dosen Tetap
Route::prefix('dosen_tetap')->middleware('auth:sanctum')->name('dosen_tetap.')->group(
    function(){
        Route::get('', [DosenTetapController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapController::class, 'create'])->name('create');
        Route::put('update/{id}', [DosenTetapController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapController::class, 'destroy'])->name('delete');

    });

// Karyawan
Route::prefix('karyawan')->middleware('auth:sanctum')->name('karyawan.')->group(
    function(){
        Route::get('', [KaryawanController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanController::class, 'create'])->name('create');
        Route::put('update/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanController::class, 'destroy'])->name('delete');

    });
