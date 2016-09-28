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

Route::get('/', function () {
    return 'Hello World';
});

Route::get('client/inspection', 'Client\InspectionController@inspection');
Route::post('client/inspection', 'Client\InspectionController@saveInspection');

Route::get('manager', 'Manager\ReferenceController@index');
Route::get('manager/dashboard', 'Manager\ReferenceController@index');
Route::get('manager/reference', 'Manager\ReferenceController@index');
Route::get('manager/mapping', 'Manager\ReferenceController@index');
Route::get('manager/report', 'Manager\ReferenceController@index');


// $api = app('Dingo\Api\Routing\Router');

// $api->version('v1', ['prefix' => 'client', 'namespace' => 'App\Http\Controllers\Client'], function ($api) {
//     $api->get('inspectorGroup', 'InspectionController@inspectorGroup');

//     $api->get('inspection', 'InspectionController@inspection');
//     $api->post('inspection', 'InspectionController@saveInspection');

//     $api->post('print1', 'PrintController@print');
//     $api->post('print2', 'PrintController@printByTemplate');
//     $api->post('print3', 'PrintController@printByHtml');

// });

// $api->version('v1', ['prefix' => 'manager', 'namespace' => 'App\Http\Controllers\Manager'], function ($api) {
//     $api->get('/', 'ReferenceController@index');
// });
