<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

// Import Model Dosen Tetap
use App\Http\Controllers\API\dosentetap\DosenTetapController;
use App\Http\Controllers\API\dosentetap\LaporanController as DosenTetapLaporanController;
use App\Http\Controllers\API\dosentetap\TransaksiGajiController as DosenTetapTransaksiGajiController;


// Import Model Karyawan
use App\Http\Controllers\API\karyawan\KaryawanController;
use App\Http\Controllers\API\karyawan\LaporanController as KaryawanLaporanController;
use App\Http\Controllers\API\karyawan\TransaksiGajiController as KaryawanTransaksiGajiController;


// Import Model Dosen Luar Biasa
use App\Http\Controllers\API\dosenlb\DosenLuarBiasaController;
use App\Http\Controllers\API\dosenlb\LaporanController as DosenlbLaporanController;
use App\Http\Controllers\API\dosenlb\TransaksiGajiController as DosenlbTransaksiGajiController;


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
    Route::put('update/{id}', [DosenTetapController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [DosenTetapController::class, 'destroy'])->name('delete');
    Route::get('/gaji/{dosentetapId}', [DosenTetapTransaksiGajiController::class, 'fetch'])->name('fetch');
    Route::get('/gaji/transaksi/{transaksiId}', [DosenTetapTransaksiGajiController::class, 'fetchById'])->name('fetchById');
    Route::post('/gaji/transaksi/create', [DosenTetapTransaksiGajiController::class, 'create'])->name('create');
    Route::put('gaji/transaksi/update/{transaksiId}', [DosenTetapTransaksiGajiController::class, 'update'])->name('update');
    Route::delete('gaji/transaksi/delete/{transaksiId}', [DosenTetapTransaksiGajiController::class, 'destroy'])->name('destroy');

});


// Laporan Dosen Tetap
Route::prefix('dosentetap-laporan')->middleware('auth:sanctum')->name('dosentetap-laporan.')->group(
    function(){
        Route::get('rekapitulasipendapatan', [DosenTetapLaporanController::class, 'rekapitulasipendapatan'])->name('rekapitulasipendapatan');
        Route::get('pendapatanbersih', [DosenTetapLaporanController::class, 'pendapatanbersih'])->name('pendapatanbersih');
        Route::get('laporanpajak', [DosenTetapLaporanController::class, 'laporanpajak'])->name('laporanpajak');
        Route::get('laporanpotongan', [DosenTetapLaporanController::class, 'laporanpotongan'])->name('laporanpotongan');
        Route::get('rekapitulasibank', [DosenTetapLaporanController::class, 'rekapitulasibank'])->name('rekapitulasibank');
    });

// Karyawan
Route::prefix('karyawan')->middleware('auth:sanctum')->name('karyawan.')->group(
    function(){
        Route::get('', [KaryawanController::class, 'fetch'])->name('fetch');
        Route::get('/{id}', [KaryawanController::class, 'fetch'])->name('fetch');
        Route::post('create', [KaryawanController::class, 'create'])->name('create');
        Route::put('update/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [KaryawanController::class, 'destroy'])->name('delete');
        Route::get('/gaji/{karyawanId}', [KaryawanTransaksiGajiController::class, 'fetch'])->name('fetch');
        Route::get('/gaji/transaksi/{transaksiId}', [KaryawanTransaksiGajiController::class, 'fetchById'])->name('fetchById');
        Route::post('/gaji/transaksi/create', [KaryawanTransaksiGajiController::class, 'create'])->name('create');
        Route::put('gaji/transaksi/update/{transaksiId}', [KaryawanTransaksiGajiController::class, 'update'])->name('update');
        Route::delete('gaji/transaksi/delete/{transaksiId}', [KaryawanTransaksiGajiController::class, 'destroy'])->name('destroy');

    });
// Laporan Karyawan
Route::prefix('karyawan-laporan')->middleware('auth:sanctum')->name('karyawan-laporan.')->group(
    function(){
        Route::get('rekapitulasipendapatan', [KaryawanLaporanController::class, 'rekapitulasipendapatan'])->name('rekapitulasipendapatan');
        Route::get('pendapatanbersih', [KaryawanLaporanController::class, 'pendapatanbersih'])->name('pendapatanbersih');
        Route::get('laporanpajak', [KaryawanLaporanController::class, 'laporanpajak'])->name('laporanpajak');
        Route::get('laporanpotongan', [KaryawanLaporanController::class, 'laporanpotongan'])->name('laporanpotongan');
        Route::get('rekapitulasibank', [KaryawanLaporanController::class, 'rekapitulasibank'])->name('rekapitulasibank');
    });


// Dosen Luar Biasa
Route::prefix('dosenlb')->middleware('auth:sanctum')->name('dosenlb.')->group(
function(){
    Route::get('', [DosenLuarBiasaController::class, 'fetch'])->name('fetch');
    Route::get('/{id}', [DosenLuarBiasaController::class, 'fetch'])->name('fetch');
    Route::post('create', [DosenLuarBiasaController::class, 'create'])->name('create');
    Route::put('update/{id}', [DosenLuarBiasaController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [DosenLuarBiasaController::class, 'destroy'])->name('delete');
    Route::get('/gaji/{dosenlbId}', [DosenlbTransaksiGajiController::class, 'fetch'])->name('fetch');
    Route::get('/gaji/transaksi/{transaksiId}', [DosenlbTransaksiGajiController::class, 'fetchById'])->name('fetchById');
    Route::post('/gaji/transaksi/create', [DosenlbTransaksiGajiController::class, 'create'])->name('create');
    Route::put('gaji/transaksi/update/{transaksiId}', [DosenlbTransaksiGajiController::class, 'update'])->name('update');
    Route::delete('gaji/transaksi/delete/{transaksiId}', [DosenlbTransaksiGajiController::class, 'destroy'])->name('destroy');
});

// Laporan Dosen Luar Biasa
Route::prefix('dosenlb-laporan')->middleware('auth:sanctum')->name('dosenlb-laporan.')->group(
    function(){
        Route::get('rekapitulasipendapatan', [DosenlbLaporanController::class, 'rekapitulasipendapatan'])->name('rekapitulasipendapatan');
        Route::get('pendapatanbersih', [DosenlbLaporanController::class, 'pendapatanbersih'])->name('pendapatanbersih');
        Route::get('laporanpajak', [DosenlbLaporanController::class, 'laporanpajak'])->name('laporanpajak');
        Route::get('laporanpotongan', [DosenlbLaporanController::class, 'laporanpotongan'])->name('laporanpotongan');
        Route::get('rekapitulasibank', [DosenlbLaporanController::class, 'rekapitulasibank'])->name('rekapitulasibank');
    });



    // Coba
    Route::prefix('transaksi')->middleware('auth:sanctum')->name('transaksi.')->group(
        function(){
            Route::get('bruto', [RekapitulasiController::class, 'pendapatanbruto'])->name('pendapatanbruto');
            // Route::get('pegawai/{bulan}/{tahun}', [TransaksiGajiController::class, 'getDataByMonthAndYear'])->name('pegawai.getDataByMonthAndYear');
        });
