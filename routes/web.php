<?php

use App\Http\Controllers\Admin\LoginController;
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

Route::get('/administrator/login', [LoginController::class, 'index'])->name('admin-login');
Route::get('/administrator/logout', [LoginController::class, 'index']);
Route::post('/administrator/login/store', [LoginController::class, 'store']);
Route::get('administrator/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
Route::get('/administrator/approve-htx', [LoginController::class, 'dashboard'])->name('approve-htx');
Route::get('/administrator/approve-post', [LoginController::class, 'dashboard'])->name('approve-post');