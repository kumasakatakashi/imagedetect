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
// Root
Route::get('/', 'ImageController@index');
Route::redirect('/home', '/');

// Auth
Auth::routes();

// UploadImage
Route::post('upload', 'ImageController@upload');
Route::get('upload', 'ImageController@index');

// DetectTextFromImage
Route::post('detect', 'ImageController@detect');
Route::get('detect', 'ImageController@index');
