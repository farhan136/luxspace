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

Route::get('/cart', 'App\Http\Controllers\FrontendController@cart')->name('cart');

Route::post('success', 'App\Http\Controllers\FrontendController@success')->name('success');

Route::middleware(['auth:sanctum', 'verified'])->name('dashboard.')->prefix('dashboard')->group(function(){
	Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('index');

	Route::middleware(['apakahadmin'])->group(function(){
		Route::resource('product', 'App\Http\Controllers\ProductController');
		Route::resource('product.gallery', 'App\Http\Controllers\ProductGalleryController')->shallow()->only([
			'index', 'create', 'store', 'destroy'
		]);
	});
});