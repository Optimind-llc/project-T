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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['prefix' => '/'], function ($api) {
    $api->get('/', function () {
	    return 'Hello World';
	});

    $api->get('/a', function () {
	    return 'a';
	});
});

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
