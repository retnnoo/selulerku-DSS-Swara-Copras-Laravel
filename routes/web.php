<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\AhliController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\CoprasController;
use App\Http\Controllers\SwaraController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UserController::class, 'index'])->name('beranda');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');


Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard-admin/admin', [AdminController::class, 'indexAdmin'])->name('admin');
    Route::post('/dashboard-admin/admin/add', [AdminController::class, 'storeAdmin'])->name('store.admin');
    Route::post('/dashboard-admin/admin/update-data/{id}', [AdminController::class, 'updateAdmin'])->name('update.admin');
    Route::delete('/dashboard-admin/admin/delete-data/{id}', [AdminController::class, 'deleteAdmin'])->name('delete.admin');

    Route::get('/dashboard-login-admin', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard-admin/kriteria', [KriteriaController::class, 'indexKriteria'])->name('kriteria');
    Route::post('/dashboard-admin/kriteria/add-data', [KriteriaController::class, 'storeKriteria'])->name('store.kriteria');
    Route::post('/dashboard-admin/kriteria/update-data/{kode_kriteria}', [KriteriaController::class, 'updateKriteria'])->name('update.kriteria');
    Route::delete('/dashboard-admin/kriteria/delete-data/{kode_kriteria}', [KriteriaController::class, 'deleteKriteria'])->name('delete.kriteria');

    Route::get('/dashboard-admin/ahli', [AhliController::class, 'indexAhli'])->name('ahli');
    Route::post('/dashboard-admin/ahli/add-data', [AhliController::class, 'storeAhli'])->name('store.ahli');
    Route::post('/dashboard-admin/ahli/update-data/{kode_ahli}', [AhliController::class, 'updateAhli'])->name('update.ahli');
    Route::delete('/dashboard-admin/ahli/delete-data/{kode_ahli}', [AhliController::class, 'deleteAhli'])->name('delete.ahli');

    Route::get('/dashboard-admin/pembobotan', [SwaraController::class, 'indexPembobotanSwara'])->name('pembobotan');
    Route::get('/dashboard-admin/ranking-copras', [CoprasController::class, 'indexCopras'])->name('copras');

    Route::get('/dashboard-admin/alternatif', [AlternatifController::class, 'indexAlternatif'])->name('alternatif');
    Route::post('/dashboard-admin/alternatif/add-data', [AlternatifController::class, 'storeAlternatif'])->name('store.alternatif');
    Route::post('/dashboard-admin/alternatif/update-data/{kode_alternatif}', [AlternatifController::class, 'updateAlternatif'])->name('update.alternatif');
    Route::delete('/dashboard-admin/alternatif/delete-data/{kode_alternatif}', [AlternatifController::class, 'deleteAlternatif'])->name('delete.alternatif');

    Route::get('/dashboard-admin/wilayah', [WilayahController::class, 'indexWilayah'])->name('wilayah');
    Route::post('/dashboard-admin/wilayah/add-data', [WilayahController::class, 'storeWilayah'])->name('store.wilayah');
    Route::post('/dashboard-admin/wilayah/update-data/{kode_wilayah}', [WilayahController::class, 'updateWilayah'])->name('update.wilayah');
    Route::delete('/dashboard-admin/wilayah/delete-data/{kode_wilayah}', [WilayahController::class, 'deleteWilayah'])->name('delete.wilayah');

});

