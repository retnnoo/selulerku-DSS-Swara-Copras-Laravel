<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/', [UserController::class, 'index'])->name('beranda');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

Route::get('/dashboard/admin/add', [AdminController::class, 'create']);
Route::post('/dashboard/admin/add', [AdminController::class, 'storeAdmin'])->name('store.admin');

Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('dashboard');
Route::get('/dashboard-admin/kriteria', [AdminController::class, 'indexKriteria'])->name('kriteria');
Route::post('/dashboard-admin/kriteria/add-data', [AdminController::class, 'storeKriteria'])->name('store.kriteria');
Route::post('/dashboard-admin/kriteria/update-data/{kode_kriteria}', [AdminController::class, 'updateKriteria'])->name('update.kriteria');
Route::delete('/dashboard-admin/kriteria/delete-data/{kode_kriteria}', [AdminController::class, 'deleteKriteria'])->name('delete.kriteria');

Route::get('/dashboard-admin/ahli', [AdminController::class, 'indexAhli'])->name('ahli');
Route::post('/dashboard-admin/ahli/add-data', [AdminController::class, 'storeAhli'])->name('store.ahli');
Route::post('/dashboard-admin/ahli/update-data/{kode_ahli}', [AdminController::class, 'updateAhli'])->name('update.ahli');
Route::delete('/dashboard-admin/ahli/delete-data/{kode_ahli}', [AdminController::class, 'deleteAhli'])->name('delete.ahli');

Route::get('/dashboard-admin/pembobotan', [AdminController::class, 'indexPembobotanSwara'])->name('pembobotan');

Route::get('/dashboard-admin/alternatif', [AdminController::class, 'indexAlternatif'])->name('alternatif');
Route::post('/dashboard-admin/alternatif/add-data', [AdminController::class, 'storeAlternatif'])->name('store.alternatif');

Route::get('/dashboard-admin/ranking-copras', [AdminController::class, 'indexCopras'])->name('copras');


Route::get('/dashboard-admin/wilayah', [AdminController::class, 'indexWilayah'])->name('wilayah');
Route::post('/dashboard-admin/wilayah/add-data', [AdminController::class, 'storeWilayah'])->name('store.wilayah');
Route::post('/dashboard-admin/wilayah/update-data/{kode_wilayah}', [AdminController::class, 'updateWilayah'])->name('update.wilayah');
Route::delete('/dashboard-admin/wilayah/delete-data/{kode_wilayah}', [AdminController::class, 'deleteWilayah'])->name('delete.wilayah');
