<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', 'App\Http\Controllers\ProductController@index')->name('products.index');
Route::get('/product', 'App\Http\Controllers\ProductController@index')->name('product.index');

// 新規登録
Route::get('/products/create', 'App\Http\Controllers\ProductController@create')->name('product.create')->middleware('auth');
Route::post('/products/store/', 'App\Http\Controllers\ProductController@store')->name('product.store')->middleware('auth');

// 編集
Route::get('/products/edit/{product}', 'App\Http\Controllers\ProductController@edit')->name('product.edit')->middleware('auth');
Route::put('/products/edit/{product}', 'App\Http\Controllers\ProductController@update')->name('product.update')->middleware('auth');

// 詳細
Route::get('/products/show/{product}', 'App\Http\Controllers\ProductController@show')->name('product.show');

// 非同期削除のための新しいルートを追加
Route::delete('/products/async-delete/{product}', 'App\Http\Controllers\ProductController@asyncDestroy')->name('product.async-destroy');

// 削除
Route::delete('/products/{product}', 'App\Http\Controllers\ProductController@destroy')->name('product.destroy')->middleware('auth');

// 非同期検索用
Route::post('/products/search', 'App\Http\Controllers\ProductController@search')->name('product.search');

// ソート
Route::get('/products/sort', 'App\Http\Controllers\ProductController@sort')->name('product.sort'); 

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
