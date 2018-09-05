<?php

Route::get('/', 'HomeController@index')->name('blog');
Route::get('post/{post}', 'PostController@show');

Route::group([
    'prefix' => 'api',
], function () {
    Route::get('post/{post}/comment', 'Api\CommentController@index');

    Route::resources([
        'post' => 'Api\PostController',
    ]);
});

Route::group([
    'middleware' => 'auth',
    'prefix' => 'api',
], function () {
    Route::post('post/{post}/comment', 'Api\CommentController@store');
    Route::put('post/{post}/comment/{comment}', 'Api\CommentController@update');
    Route::delete('post/{post}/comment/{comment}', 'Api\CommentController@destroy');
});

Route::group([
    'middleware' => 'auth',
    'prefix' => 'api/admin',
], function () {
    Route::resources([
        'category' => 'Api\Admin\CategoryController',
        'post' => 'Api\Admin\PostController',
    ]);
});
