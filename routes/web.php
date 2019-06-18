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
Auth::routes();
Route::get('/', 'ImageController@index');
Route::post('upload', 'ImageController@upload');
Route::get('upload', 'ImageController@index'); //エラー用
Route::post('detect', 'ImageController@detect');
Route::get('detect', 'ImageController@index'); //エラー用
Route::redirect('/home', '/');
