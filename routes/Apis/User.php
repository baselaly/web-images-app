<?php

Route::group(['namespace' => 'User', 'prefix' => 'user', 'as' => 'users.'], function () {
    Route::post('/register', ['as' => 'register', 'uses' => 'UserController@register']);
    Route::post('/login', ['as' => 'login', 'uses' => 'UserController@login']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
    Route::get('/activate/{token}', ['as' => 'activate', 'uses' => 'UserController@activateUser']);

    Route::group(['middleware' => 'user_auth'], function () {
        Route::post('/follow', ['as' => 'follow.store', 'uses' => 'UserFollowerController@store']);
        Route::post('/unfollow', ['as' => 'follow.delete', 'uses' => 'UserFollowerController@unFollow']);
        Route::get('/profile', ['as' => 'my.profile', 'uses' => 'UserController@getMyProfile']);
        Route::post('/edit/profile', ['as' => 'profile.edit', 'uses' => 'UserController@update']);
    });

    Route::get('/{userId}', ['as' => 'view.profile', 'uses' => 'UserController@getUserProfile']);
    Route::get('/followers/{userId}', ['as' => 'user.get.followers', 'uses' => 'UserFollowerController@getUserFollowers']);
    Route::get('/followings/{userId}', ['as' => 'user.get.followings', 'uses' => 'UserFollowerController@getUserFollowings']);
});

Route::group(['namespace' => 'User', 'prefix' => 'post', 'as' => 'posts.'], function () {
    Route::group(['middleware' => 'user_auth'], function () {
        Route::get('/home', ['as' => 'post.home', 'uses' => 'PostController@getHomePosts']);
        Route::post('/store', ['as' => 'post.store', 'uses' => 'PostController@store']);
        Route::get('/delete/{id}', ['as' => 'post.delete', 'uses' => 'PostController@delete']);
        Route::post('/update/{id}', ['as' => 'post.update', 'uses' => 'PostController@update']);

        Route::post('/like', ['as' => 'post.like', 'uses' => 'LikeController@likePost']);
    });

    Route::get('/{id}', ['as' => 'view', 'uses' => 'PostController@getPost']);
});
