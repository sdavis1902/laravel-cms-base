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

Route::get('admin', function(){
	return redirect('admin/dashboard');
});
Route::group(['middleware' => ['authcheck', 'adminviewshare']], function () {
    MoreRoute::controller('admin/dashboard', 'AdminDashboardController');
    MoreRoute::controller('admin/user', 'AdminUserController');
    MoreRoute::controller('admin/site', 'AdminSiteController');
});
