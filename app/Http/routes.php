<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        $data['menu'] = 'Home';
        return View::make('loginPage', $data)->render();
    });
});

Route::controller('login', 'LoginController');

Route::group(['middleware' => 'auth'], function () {
    Route::controller('user', 'UserController');
});

Route::group(['middleware' => 'auth.company'], function () {
    Route::get('company/notice-board/create', 'CompanyController@getNoticeBoardCreate');
    Route::get('company/notice-board/{id}/edit', 'CompanyController@getNoticeBoardEdit');
    Route::get('company/designation/{id}/edit', 'CompanyController@getDesignationEdit');
    Route::get('company/all-user/{id}/force', 'CompanyController@getForce');
    Route::post('company/all-user/{id}/force', 'CompanyController@postForce');
    Route::controller('company', 'CompanyController');
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');