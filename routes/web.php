<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

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


Route::get('/login', [SiteController::class, 'login'])->name('login');
Route::post('/login', [SiteController::class, 'auth'])->name('auth');
Route::post('/logout', [SiteController::class, 'logout'])->name('logout');
Route::group(['middleware' => 'auth.custom'], function() {
    Route::get('/', [SiteController::class, 'index'])->name('homepage');
});
