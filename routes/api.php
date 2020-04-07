<?php

Route::group(['middleware' => 'api'], function () {
    require 'Apis/User.php';
});
