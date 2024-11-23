<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartsController;

Route::get('/', function () {
    return view('welcome');
});

//Authentication İşlemleri (Kullanıcı)

Route::post('/api/auth/register', [UsersController::class, 'register'])->middleware('UsersMiddleware')->name('users_register');
Route::post('/api/auth/login', [UsersController::class, 'login'])->middleware('UsersMiddleware')->name('user_login');
Route::post('/api/auth/logout', [UsersController::class, 'logout'])->middleware('UsersMiddleware')->name('user_logout');

//Authentication İşlemleri (Admin)

Route::post('/api/admin/register', [AdminController::class, 'register'])->middleware('AdminMiddleware')->name('admin_register');
Route::post('/api/admin/login', [AdminController::class, 'login'])->middleware('AdminMiddleware')->name('admin_login');
Route::post('/api/admin/logout', [AdminController::class, 'logout'])->middleware('AdminMiddleware')->name('admin_logout');

//Ürün İşlemleri (Admin)

Route::post('/api/products', [ProductsController::class, 'add'])->middleware('ProductsMiddleware')->name('products_add');
Route::put('/api/products/{id}', [ProductsController::class, 'update'])->middleware('ProductsMiddleware')->name('products_update');
Route::delete('/api/products/{id}', [ProductsController::class, 'delete'])->middleware('ProductsMiddleware')->name('products_delete');

//Ürün işlemleri (Kullanıcı)

Route::get('/api/products', [ProductsController::class, 'list'])->name('products_list');
Route::get('/api/products/{id}', [ProductsController::class, 'listOne'])->name('products_list');


//Sepet İşlemleri

Route::post('/api/cart/items', [CartsController::class, 'add'])->middleware('CartsMiddleware')->name('carts_add');
Route::put('/api/cart/items/{id}', [CartsController::class, 'update'])->middleware('CartsMiddleware')->name('carts_update');
Route::delete('/api/cart/items/{id}', [CartsController::class, 'delete'])->middleware('CartsMiddleware')->name('carts_delete');
Route::get('/api/cart', [CartsController::class, 'list'])->middleware('CartsMiddleware')->name('carts_list');
Route::get('/api/cart/cachecartsave', [CartsController::class, 'cacheCartSave'])->middleware('CartsMiddleware')->name('cache_cart_save');


//Sipariş İşlemleri
/*
POST /api/orders
GET /api/orders
GET /api/orders/{id}
*/