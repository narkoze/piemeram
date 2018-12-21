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
Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('project', 'ProjectController@index')->name('project');
Route::get('movie', 'MovieController@index')->name('movie');
Route::get('movie/excel', 'MovieController@excel')->name('movie.excel');
Route::get('about', 'AboutController@index')->name('about');

Route::post('locale/{locale}', 'LocaleController@setLocale')->name('setLocale');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')
    ->name('logs')
    ->middleware('masterOnly');
