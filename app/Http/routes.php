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

// Route::get('/', function () {
//     return view('welcome');
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['prefix' => 'client', 'namespace' => 'App\Http\Controllers\Client'], function ($api) {
    $api->get('inspectorGroup', 'InspectionController@inspectorGroup');

    $api->get('inspection', 'InspectionController@inspection');
    $api->post('inspection', 'InspectionController@saveInspection');

    $api->post('print1', 'PrintController@print');
    $api->post('print2', 'PrintController@printByTemplate');
    $api->post('print3', 'PrintController@printByHtml');

});

$api->version('v1', ['prefix' => 'manager', 'namespace' => 'App\Http\Controllers\Manager'], function ($api) {
    $api->get('/', 'ReferenceController@index');
});

// // Publicly accessible routes
// Route::get('/user/confirm/{token}', 'User\Auth\AuthController@confirmAccount');

// $api->version('v1', ['prefix' => 'cliant', 'namespace' => 'App\Http\Controllers\Cliant'], function ($api) {
//     $api->get('base/{process}/{inspector}/{division}', 'CliantController@getBaseInfo');

//     $api->post('inspection_families', 'AuthController@signup');
//     $api->post('/signin', 'AuthController@signin');
//     $api->post('/signin/{provider}', 'AuthController@signinThirdParty');

//     $api->post('/password/initialize', 'PasswordController@initialize');
//     $api->get('/password/reset', 'PasswordController@reset');

//     $api->get('/refresh', 'AuthController@refresh');
// });






// $api->version('v1', ['namespace' => 'App\Http\Controllers\User\Auth'], function ($api) {
//     $api->get('/schools', 'AuthController@schools');

//     $api->post('/signup', 'AuthController@signup');
//     $api->post('/signin', 'AuthController@signin');
//     $api->post('/signin/{provider}', 'AuthController@signinThirdParty');

//     $api->post('/password/initialize', 'PasswordController@initialize');
//     $api->get('/password/reset', 'PasswordController@reset');

//     $api->get('/refresh', 'AuthController@refresh');
// });

// // JWT Protected routes
// $api->version('v1', ['middleware' => 'api.auth', 'namespace' => 'App\Http\Controllers', 'providers' => 'jwt'], function ($api) {
// 	$api->get('/confirm/resend', 'User\Auth\AuthController@resendConfirmationEmail');
//     $api->post('/password/change', 'User\Auth\PasswordController@change');

//     $api->get('/decode', 'PagesController@decode');
//     $api->get('/show', 'PagesController@show');

//     $api->group(['namespace' => 'User'], function ($api) {
//         $api->get('/points', 'BasicController@points');
//         $api->post('/start', 'BasicController@start');
//         $api->post('/end', 'BasicController@end');
//     });
// });

// Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

// 	Route::group(['namespace' => 'Auth'], function () {

// 	    Route::group(['middleware' => 'auth:admin'], function () {
// 	        Route::get('signout', 'AuthController@signout');
// 	        Route::post('password/change', 'PasswordController@changePassword');
// 	    });

// 	    Route::group(['middleware' => 'guest:admin'], function () {
// 	        // Authentication Routes
// 	        Route::get('signin', 'AuthController@showLoginForm');
// 	        Route::post('signin', 'AuthController@login');

// 	        // Registration Routes
// 	        Route::get('signup', 'AuthController@showRegistrationForm');
// 	        Route::post('register', 'AuthController@register');

// 	        // Password Reset Routes
// 	        Route::get('password/reset/{token?}', 'PasswordController@showResetForm');
// 	        Route::post('password/email', 'PasswordController@sendResetLinkEmail');
// 	        Route::post('password/reset', 'PasswordController@reset');
// 	    });
// 	});
// });