<?php

Route::group(['middleware' => 'api'], function () {
    require_once 'Apis/User.php';
});
