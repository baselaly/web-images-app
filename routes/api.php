<?php

Route::group(['middleware' => 'api'], function () {
    Route::group(['namespace' => 'User', 'prefix' => 'user', 'as' => 'users.'], function () {
        Route::post('/register', ['as' => 'register', 'uses' => 'UserController@register']);
        Route::post('/login', ['as' => 'login', 'uses' => 'UserController@login']);
        Route::get('/logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
    });
});
