<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth', 'checkRole:1, 2, 3']], function() {
    Route::resource('dashboard',DashboardController::class);
    Route::resource('penjualan',PenjualanController::class);
    Route::resource('profil',ProfilController::class);
});

Route::group(['middleware' => ['auth', 'checkRole:1, 2']], function() {
    Route::resource('pengguna',PenggunaController::class);
    // Route::resource('peramalan',PeramalanController::class);
    Route::resource('peramalan',PeramalanController::class);
});

Route::post('/peramalan', [PeramalanController::class, 'forecast'])->name('peramalan.forecast');
Route::post('/import-excel', [PenjualanController::class, 'import_excel'])->name('importExcel');

Route::post('/actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');
Route::get('/actionLogout', [LoginController::class, 'actionLogout'])->name('actionLogout');