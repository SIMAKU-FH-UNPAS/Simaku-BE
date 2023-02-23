<?php

use App\Http\Controllers\API\DosenLBController;
use App\Http\Controllers\API\UserController;
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
