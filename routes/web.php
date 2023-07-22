<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DokumenMasukController;
use App\Http\Controllers\DetailDokumenController;
use App\Http\Controllers\JenisBelanjaController;
use App\Http\Controllers\RegulasiController;

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

// index routing via Route feature
Route::redirect('/', '/login');

// Auth
Route::get('register', [RegisterController::class, 'index']);
Route::post('register', [RegisterController::class, 'store']);
Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'authenticate']);
Route::get('logout', [LoginController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::view('dashboard', 'dashboards/default');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('jenis-belanja', JenisBelanjaController::class);
    Route::resource('regulasi', RegulasiController::class);
    Route::resource('data-arsip', DokumenController::class);
    Route::resource('dokumen-masuk', DokumenMasukController::class);
    Route::post('data-arsip/import-excel', [DokumenController::class, 'import_excel']);
    Route::get('data-arsip/export-excel/{ext?}', [DokumenController::class, 'export_excel']);
    Route::get('detail-data-arsip/export-excel/{ext?}', [DetailDokumenController::class, 'export_excel']);
    Route::post('data-arsip/verifikasi_dokumen', [DokumenController::class, 'verification_document']);
    Route::resource('detail-data-arsip', DetailDokumenController::class);
    Route::get('get-berkas-arsip/{id?}', [DokumenController::class, 'getBerkasArsip']);
});
