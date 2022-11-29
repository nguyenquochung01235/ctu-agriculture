<?php

use App\Http\Controllers\Admin\AdminController;
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

Route::get('/', [AdminController::class, 'index']);
Route::get('/administrator/login', [AdminController::class, 'index'])->name('admin-login');
Route::get('/administrator/logout', [AdminController::class, 'index']);
Route::post('/administrator/login/store', [AdminController::class, 'store']);
Route::get('/administrator/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/administrator/approve-htx', [AdminController::class, 'hoptacxa'])->name('approve-htx');
Route::get('/administrator/hoptacxa/view/{id_hoptacxa}', [AdminController::class, 'detailHopTacXa']);
Route::post('/administrator/hoptacxa/active/{id_hoptacxa}', [AdminController::class, 'activeHopTacXa']);



Route::get('/administrator/approve-post', [AdminController::class, 'post']);
Route::get('/administrator/post/view/{id_post}', [AdminController::class, 'detailPost']);
Route::post('/administrator/post/active/{id_post}', [AdminController::class, 'activePost']);

