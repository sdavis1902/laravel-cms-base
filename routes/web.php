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
// master

Route::get('admin', function(){
	return redirect('admin/dashboard');
});

Route::group(['middleware' => ['adminviewshare']], function () {
	MoreRoute::controller('admin/auth', 'AdminAuthController');
	Route::group(['middleware' => ['authcheck']], function () {
		MoreRoute::controller('admin/dashboard', 'AdminDashboardController');
		MoreRoute::controller('admin/user', 'AdminUserController');
		MoreRoute::controller('admin/site', 'AdminSiteController');
	});
});

Route::group(['middleware' => 'getFrontMenuItems'], function(){
    Route::get('/', 'PageController@getHomePage');

    Route::get('{page}', [
        'uses' => 'PageController@getPage',
    ])->where(['page' => '^((?!admin).)*$']);
});
