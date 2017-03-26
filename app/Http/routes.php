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

// Delete inspection
Route::post('client/inspection/delete', 'Client\InspectionController@deleteInspection');



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
Route::post('client/association/check', 'Client\AssociationController@checkStatus');
Route::post('client/association/family', 'Client\AssociationController@getFamily');
Route::post('client/association/update', 'Client\AssociationController@updateFamily');








Route::get('manager/dashboard', 'Manager\ReferenceController@index');
Route::get('manager/reference', 'Manager\ReferenceController@index');
Route::get('manager/mapping', 'Manager\ReferenceController@index');
Route::get('manager/mapping/{pageType}/{itorG}', 'Manager\ReferenceController@index');
Route::get('manager/report', 'Manager\ReferenceController@index');
Route::get('manager/association', 'Manager\ReferenceController@index');
Route::get('manager/inspector', 'Manager\ReferenceController@index');
Route::get('manager/failure', 'Manager\ReferenceController@index');
Route::get('manager/modification', 'Manager\ReferenceController@index');
Route::get('manager/hole', 'Manager\ReferenceController@index');







Route::get('manager/report/{itionGId}/{date}/{itorG}', 'Manager\ReportController@report');
Route::get('manager/pdf/checkReport/{itionG_id}/{date}/{itorG_code}', 'Manager\PdfController@checkReport');



Route::get('show/mapping/panelId/{partTypeId}/{itionGId}/{itorG}/{panelId}', 'ShowController@panelIdMapping');
Route::get('show/mapping/advanced/{partTypeId}/{itionGId}/{itorG}', 'ShowController@advancedMapping');



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



Route::post('manager/association/mapping', 'Manager\MappingController@partIdMapping');






/*
 * For Maintenance
 */
Route::post('maintenance/inspector', 'Manager\MaintenanceController@inspector');
Route::post('maintenance/inspector/create', 'Manager\MaintenanceController@createInspector');
Route::post('maintenance/inspector/update', 'Manager\MaintenanceController@updateInspector');
Route::post('maintenance/inspector/{id}/activate', 'Manager\MaintenanceController@activateInspector');
Route::post('maintenance/inspector/{id}/deactivate', 'Manager\MaintenanceController@deactivateInspector');


Route::post('maintenance/failures', 'Manager\MaintenanceController@failures');
Route::post('maintenance/failure/create', 'Manager\MaintenanceController@createFailure');
Route::post('maintenance/failure/update', 'Manager\MaintenanceController@updateFailure');
Route::post('maintenance/failure/{id}/activate', 'Manager\MaintenanceController@activateFailure');
Route::post('maintenance/failure/{id}/deactivate', 'Manager\MaintenanceController@deactivateFailure');


Route::post('maintenance/modifications', 'Manager\MaintenanceController@modifications');
Route::post('maintenance/modification/create', 'Manager\MaintenanceController@createModification');
Route::post('maintenance/modification/update', 'Manager\MaintenanceController@updateModification');
Route::post('maintenance/modification/{id}/activate', 'Manager\MaintenanceController@activateModification');
Route::post('maintenance/modification/{id}/deactivate', 'Manager\MaintenanceController@deactivateModification');


Route::post('maintenance/holes', 'Manager\MaintenanceController@holes');
Route::post('maintenance/hole/create', 'Manager\MaintenanceController@createHole');
Route::post('maintenance/hole/update', 'Manager\MaintenanceController@updateHole');
Route::post('maintenance/hole/{id}/activate', 'Manager\MaintenanceController@activateHole');
Route::post('maintenance/hole/{id}/deactivate', 'Manager\MaintenanceController@deactivateHole');


/*
 * For 950A
 */
Route::group(['prefix' => '{vehicle}/client', 'namespace' => 'V2\Client'], function () {
    Route::post('inspection', 'InspectionController@getInspection');
    Route::post('inspection/save', 'InspectionController@saveInspection');
    Route::post('inspection/result', 'InspectionController@result');
    Route::post('inspection/resultWithChildren', 'InspectionController@resultWithChildren');
    Route::post('inspection/update', 'InspectionController@update');
    Route::post('inspection/delete', 'InspectionController@delete');

    // Associate Parts
    Route::post('association/check', 'AssociationController@check');
    Route::post('association/save', 'AssociationController@save');
    Route::post('association/family', 'AssociationController@getFamily');
    Route::post('association/update', 'AssociationController@updateFamily');
});

Route::group(['prefix' => 'manager/{vehicle}', 'namespace' => 'V2\Manager'], function () {
    Route::get('dashboard', 'InitialController@index');
    Route::get('mapping', 'InitialController@index');
    Route::get('reference', 'InitialController@index');
    Route::get('report', 'InitialController@index');
    Route::get('association', 'InitialController@index');

    // Maintenance
    Route::group(['prefix' => 'maintenance'], function () {
        Route::get('worker', 'InitialController@index');
        Route::get('failure', 'InitialController@index');
        Route::get('modification', 'InitialController@index');
        Route::get('holeModification', 'InitialController@index');
        Route::get('hole', 'InitialController@index');
        Route::get('inline', 'InitialController@index');
    });

    Route::get('initial', 'InitialController@all');

    Route::post('mapping/realtime', 'MappingController@realtime');
    Route::post('mapping/date', 'MappingController@byDate');
    Route::post('mapping/panelId', 'MappingController@byPanelId');

    Route::post('reference/advanced', 'ReferenceController@advanced');
    Route::post('reference/panelId', 'ReferenceController@byPanelId');


    Route::get('/check/{date}', 'ReportController@check');


    Route::post('report/check', 'ReportController@check');
    Route::get('report/export/{process}/{inspection}/{line}/{part}/{date}/{choku}', 'ReportController@export');

    Route::post('association/family/date', 'AssociationController@getFamilyByDate');

    
});






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
