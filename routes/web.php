<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
Route::get('logout', [AdminController::class, 'logout'])->name('logout');

Route::post('login', [AdminController::class, 'login']);
Route::middleware(['is_admin'])->group(function () {
    // Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('data', [CategoryController::class, 'getData'])->name('categories.data');
        Route::get('edit', [CategoryController::class, 'getEditData'])->name('categories.edit-data');

        Route::post('store', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');


    });
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('data', [ProductController::class, 'getData'])->name('products.data');
        Route::get('edit', [ProductController::class, 'getEditData'])->name('products.edit-data');
        Route::post('store', [ProductController::class, 'store'])->name('products.store');
        Route::post('destroy', [ProductController::class, 'destroy'])->name('products.destroy');

    });



});
