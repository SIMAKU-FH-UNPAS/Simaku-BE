<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
// Import Controller Dosen Tetap
use App\Http\Controllers\API\dosentetap\DosenTetapController;
use App\Http\Controllers\API\dosentetap\LaporanController as DosenTetapLaporanController;
use App\Http\Controllers\API\dosentetap\TransaksiGajiController as DosenTetapTransaksiGajiController;
use App\Http\Controllers\API\dosentetap\SlipGajiController as DosenTetapSlipGajiController;
// Import Controller Karyawan
use App\Http\Controllers\API\karyawan\KaryawanController;
use App\Http\Controllers\API\karyawan\LaporanController as KaryawanLaporanController;
use App\Http\Controllers\API\karyawan\TransaksiGajiController as KaryawanTransaksiGajiController;
use App\Http\Controllers\API\karyawan\SlipGajiController as KaryawanSlipGajiController;
// Import Controller Dosen Luar Biasa
use App\Http\Controllers\API\dosenlb\DosenLuarBiasaController;
use App\Http\Controllers\API\dosenlb\LaporanController as DosenlbLaporanController;
use App\Http\Controllers\API\dosenlb\TransaksiGajiController as DosenlbTransaksiGajiController;
use App\Http\Controllers\API\dosenlb\SlipGajiController as DosenlbSlipGajiController;
use App\Http\Controllers\API\master\FungsionalController;
use App\Http\Controllers\API\master\KinerjaController;
use App\Http\Controllers\API\master\KinerjaFungsionalController;
use App\Http\Controllers\API\master\TambahanController;
use App\Models\master\KinerjaFungsional;

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
Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });
});

// master
Route::prefix('master')->middleware('auth:sanctum')->name('master.')->group(
    function () {
        // kinerja
        Route::get('kinerja', [KinerjaController::class, 'fetch'])->name('fetch');
        Route::get('kinerja/{id}', [KinerjaController::class, 'fetchById'])->name('fetchById');
        Route::post('kinerja/create', [KinerjaController::class, 'create'])->name('create');
        Route::post('kinerja/update/{id}', [KinerjaController::class, 'update'])->name('update');
        Route::delete('kinerja/delete/{id}', [KinerjaController::class, 'destroy'])->name('delete');
    }
);

//Dosen Tetap
Route::prefix('dosentetap')->middleware('auth:sanctum')->name('dosentetap.')->group(
    function () {
        Route::get('', [DosenTetapController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenTetapController::class, 'fetchById'])->name('fetchById');
        Route::post('create', [DosenTetapController::class, 'create'])->name('create');
        Route::put('update/{id}', [DosenTetapController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenTetapController::class, 'destroy'])->name('delete');
        Route::get('gaji/{dosentetapId}', [DosenTetapTransaksiGajiController::class, 'fetch'])->name('fetch');
        Route::get('gaji/transaksi/{transaksiId}', [DosenTetapTransaksiGajiController::class, 'fetchById'])->name('fetchById');
        Route::post('gaji/transaksi/create', [DosenTetapTransaksiGajiController::class, 'create'])->name('create');
        Route::put('gaji/transaksi/update/{transaksiId}', [DosenTetapTransaksiGajiController::class, 'update'])->name('update');
        Route::delete('gaji/transaksi/delete/{transaksiId}', [DosenTetapTransaksiGajiController::class, 'destroy'])->name('destroy');
        Route::get('gaji/slip/{transaksiId}', [DosenTetapSlipGajiController::class, 'get'])->name('get');
        Route::get('gaji/slip/cetak/{transaksiId}', [DosenTetapSlipGajiController::class, 'viewPDF'])->name('viewPDF');
        Route::post('gaji/slip/kirim/{transaksiId}', [DosenTetapSlipGajiController::class, 'sendWA'])->name('sendWA');
        Route::get('laporan/rekapitulasipendapatan', [DosenTetapLaporanController::class, 'rekapitulasipendapatan'])->name('rekapitulasipendapatan');
        Route::get('laporan/pendapatanbersih', [DosenTetapLaporanController::class, 'pendapatanbersih'])->name('pendapatanbersih');
        Route::get('laporan/pajak', [DosenTetapLaporanController::class, 'laporanpajak'])->name('laporanpajak');
        Route::get('laporan/potongan', [DosenTetapLaporanController::class, 'laporanpotongan'])->name('laporanpotongan');
        Route::get('laporan/rekapitulasibank', [DosenTetapLaporanController::class, 'rekapitulasibank'])->name('rekapitulasibank');
    }
);

// Karyawan
Route::prefix('karyawan')->middleware('auth:sanctum')->name('karyawan.')->group(
    function () {
        Route::get('', [KaryawanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanController::class, 'fetchById'])->name('fetchById');
        Route::post('create', [KaryawanController::class, 'create'])->name('create');
        Route::put('update/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanController::class, 'destroy'])->name('delete');
        Route::get('gaji/{karyawanId}', [KaryawanTransaksiGajiController::class, 'fetch'])->name('fetch');
        Route::get('gaji/transaksi/{transaksiId}', [KaryawanTransaksiGajiController::class, 'fetchById'])->name('fetchById');
        Route::post('gaji/transaksi/create', [KaryawanTransaksiGajiController::class, 'create'])->name('create');
        Route::put('gaji/transaksi/update/{transaksiId}', [KaryawanTransaksiGajiController::class, 'update'])->name('update');
        Route::delete('gaji/transaksi/delete/{transaksiId}', [KaryawanTransaksiGajiController::class, 'destroy'])->name('destroy');
        Route::get('gaji/slip/{transaksiId}', [KaryawanSlipGajiController::class, 'get'])->name('get');
        Route::get('gaji/slip/cetak/{transaksiId}', [KaryawanSlipGajiController::class, 'viewPDF'])->name('viewPDF');
        Route::post('gaji/slip/kirim/{transaksiId}', [KaryawanSlipGajiController::class, 'sendWA'])->name('sendWA');
        Route::get('laporan/rekapitulasipendapatan', [KaryawanLaporanController::class, 'rekapitulasipendapatan'])->name('rekapitulasipendapatan');
        Route::get('laporan/pendapatanbersih', [KaryawanLaporanController::class, 'pendapatanbersih'])->name('pendapatanbersih');
        Route::get('laporan/pajak', [KaryawanLaporanController::class, 'laporanpajak'])->name('laporanpajak');
        Route::get('laporan/potongan', [KaryawanLaporanController::class, 'laporanpotongan'])->name('laporanpotongan');
        Route::get('laporan/rekapitulasibank', [KaryawanLaporanController::class, 'rekapitulasibank'])->name('rekapitulasibank');
    }
);

// Dosen Luar Biasa
Route::prefix('dosenlb')->middleware('auth:sanctum')->name('dosenlb.')->group(
    function () {
        Route::get('', [DosenLuarBiasaController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [DosenLuarBiasaController::class, 'fetchById'])->name('fetchById');
        Route::post('create', [DosenLuarBiasaController::class, 'create'])->name('create');
        Route::put('update/{id}', [DosenLuarBiasaController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [DosenLuarBiasaController::class, 'destroy'])->name('delete');
        Route::get('gaji/{dosenlbId}', [DosenlbTransaksiGajiController::class, 'fetch'])->name('fetch');
        Route::get('gaji/transaksi/{transaksiId}', [DosenlbTransaksiGajiController::class, 'fetchById'])->name('fetchById');
        Route::post('gaji/transaksi/create', [DosenlbTransaksiGajiController::class, 'create'])->name('create');
        Route::put('gaji/transaksi/update/{transaksiId}', [DosenlbTransaksiGajiController::class, 'update'])->name('update');
        Route::delete('gaji/transaksi/delete/{transaksiId}', [DosenlbTransaksiGajiController::class, 'destroy'])->name('destroy');
        Route::get('gaji/slip/{transaksiId}', [DosenlbSlipGajiController::class, 'get'])->name('get');
        Route::get('gaji/slip/cetak/{transaksiId}', [DosenlbSlipGajiController::class, 'viewPDF'])->name('viewPDF');
        Route::post('gaji/slip/kirim/{transaksiId}', [DosenlbSlipGajiController::class, 'sendWA'])->name('sendWA');
        Route::get('laporan/rekapitulasipendapatan', [DosenlbLaporanController::class, 'rekapitulasipendapatan'])->name('rekapitulasipendapatan');
        Route::get('laporan/pendapatanbersih', [DosenlbLaporanController::class, 'pendapatanbersih'])->name('pendapatanbersih');
        Route::get('laporan/pajak', [DosenlbLaporanController::class, 'laporanpajak'])->name('laporanpajak');
        Route::get('laporan/potongan', [DosenlbLaporanController::class, 'laporanpotongan'])->name('laporanpotongan');
        Route::get('laporan/rekapitulasibank', [DosenlbLaporanController::class, 'rekapitulasibank'])->name('rekapitulasibank');
    }
);
