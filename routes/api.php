<?php

Route::group(['middleware' => 'api'], function () {
    Route::group(['namespace' => 'User', 'prefix' => 'user', 'as' => 'users.'], function () {
        Route::post('/register', ['as' => 'register', 'uses' => 'UserController@register']);
        Route::post('/login', ['as' => 'login', 'uses' => 'UserController@login']);
        Route::get('/logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

        Route::group(['middleware' => 'user_auth'], function () {
            Route::post('/post/store', ['as' => 'post.store', 'uses' => 'PostController@store']);
            Route::post('/follow', ['as' => 'follow.store', 'uses' => 'UserFollowerController@store']);
            Route::post('/unfollow', ['as' => 'follow.delete', 'uses' => 'UserFollowerController@unFollow']);
            Route::get('/profile', ['as' => 'my.profile', 'uses' => 'UserController@getMyProfile']);
        });

        Route::get('/{userId}/{slug}', ['as' => 'view.profile', 'uses' => 'UserController@getUserProfile']);
    });
});
