<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DetailDokumenController;

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
    Route::resource('data-arsip', DokumenController::class);
    Route::post('/data-arsip/import_excel', [DokumenController::class, 'import_excel']);
    Route::resource('detail-data-arsip', DetailDokumenController::class);
});
