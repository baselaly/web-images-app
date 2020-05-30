<?php

Route::group(['namespace' => 'User', 'prefix' => 'user', 'as' => 'users.'], function () {
    Route::post('/register', ['as' => 'register', 'uses' => 'UserController@register']);
    Route::post('/login', ['as' => 'login', 'uses' => 'UserController@login']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

    Route::group(['middleware' => 'user_auth'], function () {
        Route::post('/follow', ['as' => 'follow.store', 'uses' => 'UserFollowerController@store']);
        Route::post('/unfollow', ['as' => 'follow.delete', 'uses' => 'UserFollowerController@unFollow']);
        Route::get('/profile', ['as' => 'my.profile', 'uses' => 'UserController@getMyProfile']);
    });

    Route::get('/{userId}/{slug}', ['as' => 'view.profile', 'uses' => 'UserController@getUserProfile']);
    Route::get('/get/followers/{userId}', ['as' => 'user.get.followers', 'uses' => 'UserFollowerController@getUserFollowers']);
    Route::get('/get/followings/{userId}', ['as' => 'user.get.followings', 'uses' => 'UserFollowerController@getUserFollowings']);
});

Route::group(['namespace' => 'User', 'prefix' => 'post', 'as' => 'posts.'], function () {
    Route::group(['middleware' => 'user_auth'], function () {
        Route::post('/store', ['as' => 'post.store', 'uses' => 'PostController@store']);
    });

    Route::get('/{id}', ['as' => 'view', 'uses' => 'PostController@getPublicPost']);
});
