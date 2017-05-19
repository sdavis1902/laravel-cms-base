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

MoreRoute::controller('admin/auth', 'AdminAuthController');

Route::group(['middleware' => ['authcheck', 'adminviewshare']], function () {
    MoreRoute::controller('admin/user', 'AdminUserController');
});
