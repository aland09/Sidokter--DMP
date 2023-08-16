<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DokumenMasukController;
use App\Http\Controllers\DokumenKeluarController;
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
Route::redirect('/', '/login')->name('beranda');;

// Auth
// Route::get('register', [RegisterController::class, 'index']);
// Route::post('register', [RegisterController::class, 'store']);
Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'permission']], function () {
    // Dashboard
    Route::view('dashboard', 'dashboards/default')->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('jenis-belanja', JenisBelanjaController::class);
    Route::resource('regulasi', RegulasiController::class);
    Route::resource('data-arsip', DokumenController::class);
    Route::resource('dokumen-masuk', DokumenMasukController::class);
    Route::resource('dokumen-keluar', DokumenKeluarController::class);
    Route::post('data-arsip/import-excel', [DokumenController::class, 'import_excel'])->name('data-arsip.import-excel');
    Route::get('data-arsip/export-excel/{ext?}', [DokumenController::class, 'export_excel'])->name('data-arsip.export-excel');
    Route::get('detail-data-arsip/export-excel/{ext?}', [DetailDokumenController::class, 'export_excel'])->name('detail-data-arsip.export-excel');
    Route::post('data-arsip/verifikasi_dokumen', [DokumenController::class, 'verification_document'])->name('data-arsip.verifikasi_dokumen');
    Route::resource('detail-data-arsip', DetailDokumenController::class);
    Route::get('get-berkas-arsip/{id?}', [DokumenController::class, 'getBerkasArsip'])->name('get-berkas-arsip');
    Route::post('import-monitoring', [DokumenController::class, 'import_monitoring'])->name('import-monitoring');
    Route::get('get-no-box/{year?}', [DokumenMasukController::class, 'get_no_box'])->name('get-no-box');
    Route::post('data-arsip-no-box', [DokumenMasukController::class, 'update_no_box'])->name('data-arsip-no-box');

});
