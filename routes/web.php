<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
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

Route::get('/product',[ProductController::class, 'index'] )->name('product.index');
Route::get('/product/create',[ProductController::class, 'create'] )->name('product.create');
Route::post('/product',[ProductController::class, 'store'] )->name('product.store');
Route::get('/product/{product}/edit',[ProductController::class, 'edit'] )->name('product.edit');
Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('product.update');
Route::delete('/product/{product}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');
Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
Route::post('/category/check', [CategoryController::class, 'check'])->name('category.check');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get("/account/login",[LoginController::class,'index'])->name("account.login");
Route::post("/account/authenticate",[LoginController::class,'authenticate'])->name("account.authenticate");
Route::get("/account/register",[LoginController::class,'register'])->name("account.register");
Route::post("/account/process-register",[LoginController::class,'processRegister'])->name("account.processRegister");



