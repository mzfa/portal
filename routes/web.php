<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfesiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\VariabelController;

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


Route::get('/', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::get('/login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('login.store');
Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'index'])->name('register');
Route::post('/register', [\App\Http\Controllers\RegisterController::class, 'store'])->name('register.store');
Route::get('/cek_surat_dong/{id}', [\App\Http\Controllers\LoginController::class, 'cek_surat_dong'])->name('cek_surat');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    
    return redirect('/');
})->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // route::group()->middleware();
    Route::controller(HakAksesController::class)->middleware('cek_login:hakakses.index')->group(function () {
        Route::get('/hakakses', 'index')->name('hakakses.index');
        Route::get('/hakakses/edit/{id}', 'edit');
        Route::get('/hakakses/delete/{id}', 'delete');
        Route::get('/hakakses/modul_akses/{id}', 'modul_akses');
        Route::post('/hakakses/modul_akses', 'modul_akses_store');
        Route::post('/hakakses/store', 'store');
        Route::post('/hakakses/update', 'update');
    });
    Route::controller(MenuController::class)->middleware('cek_login:menu.index')->group(function () {
        Route::get('/menu', 'index')->name('menu.index');
        Route::get('/menu/edit/{id}', 'edit');
        Route::get('/menu/status/{id}', 'status');
        Route::get('/menu/delete/{id}', 'delete');
        Route::post('/menu/store', 'store');
        Route::post('/menu/update', 'update');
    });
    Route::controller(UserController::class)->middleware('cek_login:user.index')->group(function () {
        Route::get('/user', 'index')->name('user.index');
        Route::get('/user/sync', 'sync');
        Route::get('/user/edit/{id}', 'edit');
        Route::post('/user/update', 'update');
    });
    Route::controller(BagianController::class)->middleware('cek_login:bagian.index')->group(function () {
        Route::get('/bagian', 'index')->name('bagian.index');
        Route::get('/bagian/sync', 'sync');
    });
    Route::controller(ProfesiController::class)->middleware('cek_login:profesi.index')->group(function () {
        Route::get('/profesi', 'index')->name('profesi.index');
        Route::get('/profesi/sync', 'sync');
    });
    Route::controller(PegawaiController::class)->middleware('cek_login:pegawai.index')->group(function () {
        Route::get('/pegawai', 'index')->name('pegawai.index');
        Route::get('/pegawai/sync', 'sync');
    });
    Route::controller(VariabelController::class)->middleware('cek_login:variabel.index')->group(function () {
        Route::get('/variabel', 'index')->name('variabel.index');
        Route::get('/variabel/edit/{id}', 'edit');
        Route::get('/variabel/status/{id}', 'status');
        Route::get('/variabel/delete/{id}', 'delete');
        Route::post('/variabel/store', 'store');
        Route::post('/variabel/update', 'update');
    });
    Route::controller(PortalController::class)->middleware('cek_login:portal.index')->group(function () {
        Route::get('/portal', 'index')->name('portal.index');
        Route::get('/portal/edit/{id}', 'edit');
        Route::get('/portal/status/{id}', 'status');
        Route::get('/portal/delete/{id}', 'delete');
        Route::post('/portal/store', 'store');
        Route::post('/portal/update', 'update');
    });
});

