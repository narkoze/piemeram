<?php

Route::get('/', 'HomeController@index')->name('blog');
Route::get('/post/{post}', 'PostController@show');

Route::group([
    'prefix' => 'api'
], function () {
    Route::resources([
        'post' => 'Api\PostController',
    ]);
});

Route::group([
    'middleware' => 'auth',
    'prefix' => 'api/admin'
], function () {
    Route::resources([
        'category' => 'Api\Admin\CategoryController',
        'post' => 'Api\Admin\PostController',
    ]);
});
