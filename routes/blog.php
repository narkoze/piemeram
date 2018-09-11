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
    Route::get('dashboard/users', 'Api\Admin\DashboardController@users');
    Route::get('dashboard/posts', 'Api\Admin\DashboardController@posts');
    Route::get('dashboard/comments', 'Api\Admin\DashboardController@comments');
    Route::get('dashboard/categories', 'Api\Admin\DashboardController@categories');

    Route::resources([
        'dashboard' => 'Api\Admin\DashboardController',
        'category' => 'Api\Admin\CategoryController',
        'post' => 'Api\Admin\PostController',
    ]);
});
