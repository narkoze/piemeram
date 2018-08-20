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

Route::get('/', 'HomeController@index');
Route::get('project', 'ProjectController@index');
Route::get('about', 'AboutController@index')->name('about');
Route::post('locale/{locale}', 'LocaleController@setLocale');
