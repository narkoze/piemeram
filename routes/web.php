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
Route::get('about', 'AboutController@index')->name('about');

Route::post('locale/{locale}', 'LocaleController@setLocale')->name('setLocale');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')
    ->name('logs')
    ->middleware('onlyMaster');

Route::group([
    'prefix' => 'project',
], function () {
    Route::get('', 'Project\ProjectController@index')->name('project');
    Route::get('movie/excel', 'Project\MovieController@excel')->name('movie.excel');
    Route::delete('movie/multiple', 'Project\MovieController@destroyMultiple')->name('movie.destroy.multiple');
    Route::resource('movie', 'Project\MovieController');
});
