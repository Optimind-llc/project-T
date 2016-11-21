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
    return 'failure mapping system';
});

// Get infomations for inspection 
Route::get('client/inspection/{itionG_id}', 'Client\InspectionController@inspection')
	->where([
		'itionG_id' => '[0-9]+'
	]);

Route::get('client/i2/{itionG_id}', 'Client\InspectionController@inspection2')
	->where([
		'itionG_id' => '[0-9]+'
	]);

Route::get('client/history/{inspectionGroupId}/{partTypeId}/{panelId}', 'Client\InspectionController@history')
	->where([
		'inspectionGroupId' => '[0-9]+',
		'partTypeId' => '[0-9]+',
		'panelId' => '[a-zA-Z0-9]+'
	]);

Route::post('client/history', 'Client\InspectionController@history');


// Save inspection data
Route::post('client/inspection', 'Client\InspectionController@saveInspection');

// Update inspection data
Route::post('client/inspection/update', 'Client\InspectionController@updateInspection');




// Print iPad display clone
Route::post('client/print', 'Client\PrintController@printByTemplate');

// Associate Parts
Route::post('client/association', 'Client\AssociationController@saveAssociation');



Route::get('manager/dashboard', 'Manager\ReferenceController@index');
Route::get('manager/reference', 'Manager\ReferenceController@index');
Route::get('manager/mapping', 'Manager\ReferenceController@index');
Route::get('manager/mapping/{pageType}/{itorG}', 'Manager\ReferenceController@index');
Route::get('manager/report', 'Manager\ReferenceController@index');
Route::get('manager/association', 'Manager\ReferenceController@index');

// Old Report PDF method
Route::get('manager/pdf/report/{itionG_id}/{date}/{itorG_code}', 'Manager\PdfController@report');

// New Report PDF method
Route::get('manager/report/{itionGId}/{date}/{itorG}', 'Manager\ReportController@report')
  ->where([
    'itionGId' => '[0-9]+'
  ]);




Route::get('manager/pdf/checkReport/{itionG_id}/{date}/{itorG_code}', 'Manager\PdfController@checkReport');

Route::get('show/page2/{partTypeId}/{itionGId}/{itorG}', 'ShowController@page2')
  ->where([
    'partTypeId' => '[0-9]+',
    'itionGId' => '[0-9]+',
    'itorG' => '[a-zA-Z]+'
  ]);

Route::get('show/panelIdSerch/{partTypeId}/{itionGId}/{panelId}', 'ShowController@panelIdSerch')
  ->where([
    'partTypeId' => '[0-9]+',
    'itionGId' => '[0-9]+',
    'panelId' => '[a-zA-Z0-9]+'
  ]);

Route::post('show/advancedSerch/{partTypeId}/{itionGId}', 'ShowController@advancedSerch')
  ->where([
    'partTypeId' => '[0-9]+',
    'itionGId' => '[0-9]+'
  ]);

Route::get('show/failures/{itionGId}', 'ShowController@failures')
  ->where([
    'itionGId' => '[0-9]+'
  ]);

Route::get('show/modifications/{itionGId}', 'ShowController@modifications')
  ->where([
    'itionGId' => '[0-9]+'
  ]);





Route::get('show', 'ShowController@tableData');
Route::get('show/pageType/{vehicle}/{process}/{inspection}/{division}/{line?}', 'ShowController@pageType')
	->where([
		'vehicle' => '[a-zA-Z0-9]+',
		'process' => '[a-z]+',
		'inspection' => '[a-z_]+',
		'division' => '[a-z_]+',
		'line' => '[0-9]+'
	]);

Route::get('show/inspectionGroup', 'ShowController@inspectionGroup');
Route::get('show/allInspectionGroupNow', 'ShowController@allInspectionGroupNow');

Route::post('show/partFamily', 'ShowController@partFamily');
Route::get('show/test', 'ShowController@test');










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
