<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\dosentetap\PajakController as DosenTetapPajakController;
use App\Http\Controllers\API\dosentetap\GajiFakController as DosenTetapGajiFakController;
use App\Http\Controllers\API\dosentetap\GajiUnivController as DosenTetapGajiUnivController;
use App\Http\Controllers\API\dosentetap\DosenTetapController;
use App\Http\Controllers\API\dosentetap\HonorFakTambahanController as DosenTetapHonorFakTambahanController;
use App\Http\Controllers\API\dosentetap\PotonganController as DosenTetapPotonganController;
use App\Http\Controllers\API\dosentetap\PotonganTambahanController as DosenTetapPotonganTambahanController;
use App\Http\Controllers\API\karyawan\GajiUnivController as KaryawanGajiUnivController;
use App\Http\Controllers\API\karyawan\GajiFakController as KaryawanGajiFakController;
use App\Http\Controllers\API\karyawan\HonorFakTambahanController as KaryawanHonorFakTambahanController;
use App\Http\Controllers\API\karyawan\PotonganController as KaryawanPotonganController;
use App\Http\Controllers\API\karyawan\PotonganTambahanController as KaryawanPotonganTambahanController;
use App\Http\Controllers\API\karyawan\PajakController as KaryawanPajakController;
use App\Http\Controllers\API\karyawan\KaryawanController;

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

//Dosen Tetap
Route::prefix('dosentetap')->middleware('auth:sanctum')->name('dosentetap.')->group(
function(){
    Route::get('', [DosenTetapController::class, 'fetch'])->name('fetch');
    Route::get('/{id}', [DosenTetapController::class, 'fetch'])->name('fetch');
    Route::post('create', [DosenTetapController::class, 'create'])->name('create');
    Route::post('update/{id}', [DosenTetapController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [DosenTetapController::class, 'destroy'])->name('delete');

});

//Gaji Universitas Dosen Tetap
Route::prefix('dosentetap-gajiuniv')->middleware('auth:sanctum')->name('dosentetap-gajiuniv.')->group(
    function(){
        Route::get('', [DosenTetapGajiUnivController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapGajiUnivController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapGajiUnivController::class, 'create'])->name('create');
        Route::post('update/{id}', [DosenTetapGajiUnivController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapGajiUnivController::class, 'destroy'])->name('delete');

    });


//Gaji Fakultas Dosen Tetap
Route::prefix('dosentetap-gajifak')->middleware('auth:sanctum')->name('dosentetap-gajifak.')->group(
    function(){
        Route::get('', [DosenTetapGajiFakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapGajiFakController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapGajiFakController::class, 'create'])->name('create');
        Route::post('update/{id}', [DosenTetapGajiFakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapGajiFakController::class, 'destroy'])->name('delete');
    });

//Honor Fakultas Tambahan Dosen Tetap
Route::prefix('dosentetap-honorfaktambahan')->middleware('auth:sanctum')->name('dosentetap-honorfaktambahan.')->group(
    function(){
        Route::get('', [DosenTetapHonorFakTambahanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapHonorFakTambahanController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapHonorFakTambahanController::class, 'create'])->name('create');
        Route::post('update/{id}', [DosenTetapHonorFakTambahanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapHonorFakTambahanController::class, 'destroy'])->name('delete');
    });


//Potongan Gaji Dosen Tetap
Route::prefix('dosentetap-potongan')->middleware('auth:sanctum')->name('dosentetap-potongan.')->group(
    function(){
        Route::get('', [DosenTetapPotonganController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapPotonganController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapPotonganController::class, 'create'])->name('create');
        Route::post('update/{id}', [DosenTetapPotonganController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapPotonganController::class, 'destroy'])->name('delete');

    });

//Potongan Tambahan Dosen Tetap
Route::prefix('dosentetap-potongantambahan')->middleware('auth:sanctum')->name('dosentetap-potongantambahan.')->group(
    function(){
        Route::get('', [DosenTetapPotonganTambahanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapPotonganTambahanController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapPotonganTambahanController::class, 'create'])->name('create');
        Route::post('update/{id}', [DosenTetapPotonganTambahanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapPotonganTambahanController::class, 'destroy'])->name('delete');
    });


//Pajak Dosen Tetap
Route::prefix('dosentetap-pajak')->middleware('auth:sanctum')->name('dosentetap-pajak.')->group(
    function(){
        Route::get('', [DosenTetapPajakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapPajakController::class, 'fetch'])->name('fetch');
        Route::post('create', [DosenTetapPajakController::class, 'create'])->name('create');
        Route::post('update/{id}', [DosenTetapPajakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapPajakController::class, 'destroy'])->name('delete');
    });

// Karyawan
Route::prefix('karyawan')->middleware('auth:sanctum')->name('karyawan.')->group(
    function(){
        Route::get('', [KaryawanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanController::class, 'destroy'])->name('delete');

    });
// Gaji Universitas Karyawan
Route::prefix('karyawan-gajiuniv')->middleware('auth:sanctum')->name('karyawan-gajiuniv.')->group(
    function(){
        Route::get('', [KaryawanGajiUnivController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanGajiUnivController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanGajiUnivController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanGajiUnivController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanGajiUnivController::class, 'destroy'])->name('delete');
    });
// Gaji Fakultas Karyawan
Route::prefix('karyawan-gajifak')->middleware('auth:sanctum')->name('karyawan-gajifak.')->group(
    function(){
        Route::get('', [KaryawanGajiFakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanGajiFakController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanGajiFakController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanGajiFakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanGajiFakController::class, 'destroy'])->name('delete');
    });
// Honor Fakultas Tambahan Karyawan
Route::prefix('karyawan-honorfaktambahan')->middleware('auth:sanctum')->name('karyawan-honorfaktambahan.')->group(
    function(){
        Route::get('', [KaryawanHonorFakTambahanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanHonorFakTambahanController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanHonorFakTambahanController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanHonorFakTambahanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanHonorFakTambahanController::class, 'destroy'])->name('delete');
    });
//Potongan Gaji Karyawan
Route::prefix('karyawan-potongan')->middleware('auth:sanctum')->name('karyawan-potongan.')->group(
    function(){
        Route::get('', [KaryawanPotonganController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanPotonganController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanPotonganController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanPotonganController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanPotonganController::class, 'destroy'])->name('delete');

    });
//Potongan Tambahan Karyawan
Route::prefix('karyawan-potongantambahan')->middleware('auth:sanctum')->name('karyawan-potongantambahan.')->group(
    function(){
        Route::get('', [KaryawanPotonganTambahanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanPotonganTambahanController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanPotonganTambahanController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanPotonganTambahanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanPotonganTambahanController::class, 'destroy'])->name('delete');
    });
//Pajak Karyawan
Route::prefix('karyawan-pajak')->middleware('auth:sanctum')->name('karyawan-pajak.')->group(
    function(){
        Route::get('', [KaryawanPajakController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanPajakController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanPajakController::class, 'create'])->name('create');
        Route::post('update/{id}', [KaryawanPajakController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanPajakController::class, 'destroy'])->name('delete');
    });

    // Coba
    Route::prefix('transaksi')->middleware('auth:sanctum')->name('transaksi.')->group(
        function(){
            Route::get('bruto', [RekapitulasiController::class, 'pendapatanbruto'])->name('pendapatanbruto');
            // Route::get('pegawai/{bulan}/{tahun}', [TransaksiGajiController::class, 'getDataByMonthAndYear'])->name('pegawai.getDataByMonthAndYear');
        });
