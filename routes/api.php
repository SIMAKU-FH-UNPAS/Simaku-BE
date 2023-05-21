<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PajakController;
use App\Http\Controllers\API\GajiFakController;
use App\Http\Controllers\API\PegawaiController;
use App\Http\Controllers\API\GajiUnivController;
use App\Http\Controllers\API\GolonganController;
use App\Http\Controllers\API\PotonganController;

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

//Gaji Universitas
Route::prefix('gajiuniv')->middleware('auth:sanctum')->name('gajiuniv.')->group(
    function(){
        Route::get('', [GajiUnivController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [GajiUnivController::class, 'fetch'])->name('fetch');
        Route::post('create', [GajiUnivController::class, 'create'])->name('create');
        Route::post('update/{id}', [GajiUnivController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [GajiUnivController::class, 'destroy'])->name('delete');

    });


//Gaji Fakultas
Route::prefix('gajifak')->middleware('auth:sanctum')->name('gajifak.')->group(
    function(){
        Route::get('', [GajiFakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [GajiFakController::class, 'fetch'])->name('fetch');
        Route::post('create', [GajiFakController::class, 'create'])->name('create');
        Route::post('update/{id}', [GajiFakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [GajiFakController::class, 'destroy'])->name('delete');
    });

//Potongan Gaji
Route::prefix('potongan')->middleware('auth:sanctum')->name('potongan.')->group(
    function(){
        Route::get('', [PotonganController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [PotonganController::class, 'fetch'])->name('fetch');
        Route::post('create', [PotonganController::class, 'create'])->name('create');
        Route::post('update/{id}', [PotonganController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [PotonganController::class, 'destroy'])->name('delete');

    });
//Pajak
Route::prefix('pajak')->middleware('auth:sanctum')->name('pajak.')->group(
    function(){
        Route::get('', [PajakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [PajakController::class, 'fetch'])->name('fetch');
        Route::post('create', [PajakController::class, 'create'])->name('create');
        Route::post('update/{id}', [PajakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [PajakController::class, 'destroy'])->name('delete');
    });


    // // Coba
    // Route::prefix('cobain')->middleware('auth:sanctum')->name('cobain.')->group(
    //     function(){
    //         Route::get('', [GajiFakController::class, 'total_gaji'])->name('total_gaji');
    //     });
