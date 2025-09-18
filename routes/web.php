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
Route::post('/dashboard-admin/kriteria/update-data/{kode_kriteria}', [AdminController::class, 'updateKriteria'])->name('update..kriteria');

Route::get('/dashboard-admin/ahli', [AdminController::class, 'indexAhli'])->name('ahli');
Route::post('/dashboard-admin/ahli/add-ahli', [AdminController::class, 'storeAhli'])->name('store.ahli');

Route::get('/dashboard-admin/pembobotan', [AdminController::class, 'indexPembobotanSwara'])->name('pembobotan');

Route::get('/dashboard-admin/alternatif', [AdminController::class, 'indexAlternatif'])->name('alternatif');
Route::get('/dashboard-admin/ranking-copras', [AdminController::class, 'indexCopras'])->name('copras');