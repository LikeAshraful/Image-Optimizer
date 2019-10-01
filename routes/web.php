<?php

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

Route::get('/', 'PhotosController@index');
Route::post('/photos', 'PhotosController@store');
Route::get('/download_img', 'PhotosController@download_images')->name('img_download');
