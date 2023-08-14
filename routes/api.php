<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\dosentetap\PajakController;
use App\Http\Controllers\API\dosentetap\GajiFakController;
use App\Http\Controllers\API\dosentetap\GajiUnivController;
use App\Http\Controllers\API\dosentetap\DosenTetapController;
use App\Http\Controllers\API\dosentetap\HonorFakTambahanController;
use App\Http\Controllers\API\dosentetap\PotonganController;
use App\Http\Controllers\API\dosentetap\PotonganTambahanController;


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
Route::prefix('dosentetap')->middleware('auth:sanctum')->name('dosentetap.')->group(
function(){
    Route::get('', [DosenTetapController::class, 'fetch'])->name('fetch');
    Route::get('/{id}', [DosenTetapController::class, 'fetch'])->name('fetch');
    Route::post('create', [DosenTetapController::class, 'create'])->name('create');
    Route::post('update/{id}', [DosenTetapController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [DosenTetapController::class, 'destroy'])->name('delete');

});

//Gaji Universitas
Route::prefix('dosentetap-gajiuniv')->middleware('auth:sanctum')->name('dosentetap-gajiuniv.')->group(
    function(){
        Route::get('', [GajiUnivController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [GajiUnivController::class, 'fetch'])->name('fetch');
        Route::post('create', [GajiUnivController::class, 'create'])->name('create');
        Route::post('update/{id}', [GajiUnivController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [GajiUnivController::class, 'destroy'])->name('delete');

    });


//Gaji Fakultas
Route::prefix('dosentetap-gajifak')->middleware('auth:sanctum')->name('dosentetap-gajifak.')->group(
    function(){
        Route::get('', [GajiFakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [GajiFakController::class, 'fetch'])->name('fetch');
        Route::post('create', [GajiFakController::class, 'create'])->name('create');
        Route::post('update/{id}', [GajiFakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [GajiFakController::class, 'destroy'])->name('delete');
    });

//Honor Fakultas Tambahan
Route::prefix('dosentetap-honorfaktambahan')->middleware('auth:sanctum')->name('dosentetap-honorfaktambahan.')->group(
    function(){
        Route::get('', [HonorFakTambahanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [HonorFakTambahanController::class, 'fetch'])->name('fetch');
        Route::post('create', [HonorFakTambahanController::class, 'create'])->name('create');
        Route::post('update/{id}', [HonorFakTambahanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [HonorFakTambahanController::class, 'destroy'])->name('delete');
    });


//Potongan Gaji
Route::prefix('dosentetap-potongan')->middleware('auth:sanctum')->name('dosentetap-potongan.')->group(
    function(){
        Route::get('', [PotonganController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [PotonganController::class, 'fetch'])->name('fetch');
        Route::post('create', [PotonganController::class, 'create'])->name('create');
        Route::post('update/{id}', [PotonganController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [PotonganController::class, 'destroy'])->name('delete');

    });

//Potongan Tambahan
Route::prefix('dosentetap-potongantambahan')->middleware('auth:sanctum')->name('dosentetap-potongantambahan.')->group(
    function(){
        Route::get('', [PotonganTambahanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [PotonganTambahanController::class, 'fetch'])->name('fetch');
        Route::post('create', [PotonganTambahanController::class, 'create'])->name('create');
        Route::post('update/{id}', [PotonganTambahanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [PotonganTambahanController::class, 'destroy'])->name('delete');
    });


//Pajak
Route::prefix('dosentetap-pajak')->middleware('auth:sanctum')->name('dosentetap-pajak.')->group(
    function(){
        Route::get('', [PajakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [PajakController::class, 'fetch'])->name('fetch');
        Route::post('create', [PajakController::class, 'create'])->name('create');
        Route::post('update/{id}', [PajakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [PajakController::class, 'destroy'])->name('delete');
    });


    // Coba
    Route::prefix('transaksi')->middleware('auth:sanctum')->name('transaksi.')->group(
        function(){
            Route::get('bruto', [RekapitulasiController::class, 'pendapatanbruto'])->name('pendapatanbruto');
            // Route::get('pegawai/{bulan}/{tahun}', [TransaksiGajiController::class, 'getDataByMonthAndYear'])->name('pegawai.getDataByMonthAndYear');
        });
