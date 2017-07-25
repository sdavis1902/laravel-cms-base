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
/*
 *Admin Routes
 */

Route::get('admin', function(){
    return redirect('admin/dashboard');
});

Route::group(['middleware' => ['adminviewshare']], function () {
    MoreRoute::controller('admin/auth', 'Admin\AuthController');
    Route::group(['middleware' => ['authcheck']], function () {
        MoreRoute::controller('admin/dashboard', 'Admin\DashboardController');
        MoreRoute::controller('admin/user', 'Admin\UserController');
        MoreRoute::controller('admin/site', 'Admin\SiteController');
    });
});


/*
 *Front end routes
 */


/*
 *when no specific route it will look for cms page in the db
 */
Route::group(['middleware' => 'getFrontMenuItems'], function(){
    Route::get('/', 'Front\PageController@getHomePage');

    Route::get('{page}', [
        'uses' => 'Front\PageController@getPage',
    ])->where(['page' => '^((?!admin).)*$']);
});
