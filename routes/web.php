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

Route::get('/', function () {
    return view('form');
})->name('base');
Route::post('/', 'ZoomApiController@createMeeting');

Route::get('confirm', function () {
    return view('formconfirm');
})->name('confirm');

Route::get('form/alter', 'ZoomApiController@change');
Route::get('form/delete', 'ZoomApiController@deleteConfirm')->name('deleteConfirm');
Route::post('form/delete', 'ZoomApiController@deleteComplete')->name('deleteConfirm');

Route::get('form/delete/complete', function () {
    return view('deletecomplete');
})->name('deletecomplete');

Route::group(['middleware' => 'auth'], function ($router) {
    Route::get('admin', 'AdminController@index')->name('amdin');
    Route::get('admin/user', 'ZoomApiController@me');


    Route::get('zoomoatuh/check', 'AdminController@zoomOauth')->name('zoomOauth');
    Route::get('zoomoatuh/getoauth', 'AdminController@getOauth')->name('getOauth');
});

// Route::get('/login', function () {
//     return view('login');
// })->name('login');

Route::get('login', 'Auth\LoginController@loginWindow')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'LogoutController@logout');
