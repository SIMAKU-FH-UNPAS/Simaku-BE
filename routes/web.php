<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\API\dosentetap\SlipGajiController as DosenTetapSlipGajiController;
use App\Http\Controllers\API\karyawan\SlipGajiController as KaryawanSlipGajiController;
use App\Http\Controllers\API\dosenlb\SlipGajiController as DosenlbSlipGajiController;
use App\Http\Controllers\API\pegawai\SlipGajiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ('Welcome to SIMAKU FH UNPAS Backend!');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// Slip Gaji Dosen Tetap
Route::get('pegawai/gaji/slip/pdf/{transaksiId}', [SlipGajiController::class, 'generatePDF'])->name('generatePDF');
// Slip Gaji Karyawan
Route::get('karyawan/gaji/slip/pdf/{transaksiId}', [KaryawanSlipGajiController::class, 'generatePDF'])->name('generatePDF');
// Slip Gaji DosenLuarBiasa
Route::get('dosenlb/gaji/slip/pdf/{transaksiId}', [DosenlbSlipGajiController::class, 'generatePDF'])->name('generatePDF');
