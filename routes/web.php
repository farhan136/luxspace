<?php

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

// Route::get('/', function () {return view('welcome');});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('/', 'App\Http\Controllers\FrontendController@index')->name('index');

Route::get('/details/{slug}', 'App\Http\Controllers\FrontendController@details')->name('details');


Route::middleware(['auth:sanctum', 'verified'])->group(function(){
	Route::get('/checkout/success', 'App\Http\Controllers\FrontendController@success')->name('checkout-success');
	Route::post('/cart/{id}', 'App\Http\Controllers\FrontendController@cartAdd')->name('cart-add');
	Route::delete('/cart/{id}', 'App\Http\Controllers\FrontendController@cartDelete')->name('cart-delete');
	Route::post('/checkout', 'App\Http\Controllers\FrontendController@checkout')->name('checkout');
	Route::get('/cart', 'App\Http\Controllers\FrontendController@cart')->name('cart');
});

Route::post('success', 'App\Http\Controllers\FrontendController@success')->name('success');

Route::middleware(['auth:sanctum', 'verified'])->name('dashboard.')->prefix('dashboard')->group(function(){
	Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('index');
	Route::resource('mytransaction', 'App\Http\Controllers\MyTransactionController')->only([
			'index', 'show'
		]);

	Route::middleware(['apakahadmin'])->group(function(){
		Route::resource('product', 'App\Http\Controllers\ProductController');
		Route::resource('product.gallery', 'App\Http\Controllers\ProductGalleryController')->shallow()->only([
			'index', 'create', 'store', 'destroy'
		]);
		Route::resource('transaction', 'App\Http\Controllers\TransactionController')->only([
			'index', 'show', 'edit', 'update'
		]);
		Route::resource('user', 'App\Http\Controllers\UserController')->only([
			'index','edit', 'destroy', 'update'
		]);
	});
});