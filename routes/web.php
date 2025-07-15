<?php

use App\Http\Controllers\SchoolController;
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

    Route::get('/school/index', [SchoolController::class, 'index'])->name('school.index');
    Route::get('/school/create', [SchoolController::class, 'create'])->name('school.create');
    Route::post('/school/store', [SchoolController::class, 'store'])->name('school.store');
    Route::get('/school/edit/{id}', [SchoolController::class, 'edit'])->name('school.edit');
    Route::post('/school/update/{id}', [SchoolController::class, 'update'])->name('school.update');
    Route::delete('/school/delete/{id}', [SchoolController::class, 'delete'])->name('school.delete');
    Route::get('/school/show/{id}', [SchoolController::class, 'show'])->name('school.show');

});
