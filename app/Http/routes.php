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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('/pc/index', [
    'as' => 'pc.index',
    'uses' => 'PropertyCustodian@index',
    'middleware' => 'auth'
]);

Route::get('/teacher/index', [
    'as' => 'teacher.index',
    'uses' => 'Teacher@index',
    'middleware' => 'auth'
]);


Route::get('/dean/index', [
    'as' => 'dean.index',
    'uses' => 'Dean@index',
    'middleware' => 'auth'
]);


Route::get('/school_president/index', [
    'as' => 'school_president.index',
    'uses' => 'SchoolPresident@index',
    'middleware' => 'auth'
]);


Route::get('/finance/index', [
    'as' => 'finance.index',
    'uses' => 'Finance@index',
    'middleware' => 'auth'
]);

Route::get('/admin/index', [
    'as' => 'admin.index',
    'uses' => 'Admin@index',
    'middleware' => 'auth'
]);



//Property Custodian

Route::get('pc/inventory','PropertyCustodian@inventory');
Route::get('pc/release_data','PropertyCustodian@GetReleaseData');
Route::get('pc/for_release_data','PropertyCustodian@GetForReleaseData');
Route::post('pc/check_inventory','PropertyCustodian@checkInventory');
Route::post('pc/approved_supplies','PropertyCustodian@GetApprovedRequest');
Route::post('pc/for_release_supplies','PropertyCustodian@ForReleaseRequest');
Route::post('pc/create_inventory','PropertyCustodian@InventoryCreate');
Route::get('pc/get_inventory','PropertyCustodian@GetInventory');
Route::post('pc/update_inventory','PropertyCustodian@InventoryUpdate');
Route::post('pc/destroy','PropertyCustodian@destroy');
Route::get('pc/pc/check_status_request','PropertyCustodian@CheckedStatusRequestData');
Route::get('pc/scanner','PropertyCustodian@Scanner');
Route::get('pc/generate-barcode/{id}', 'PropertyCustodian@generateBarcode');
Route::post('pc/barcode_upload','PropertyCustodian@uploadBarcode');






// Teacher

Route::get('teacher/request','Teacher@request');
Route::post('teacher/create_request','Teacher@Createrequest');
Route::get('teacher/track_request','Teacher@GetTrackingRequest');
Route::get('teacher/teacher/check_status','Teacher@CheckedStatusRequest');



// Dean
Route::get('dean/request_data','Dean@GetData');
Route::get('dean/get_request','Dean@GetRequest');
Route::post('dean/approved_supplies','Dean@GetApprovedRequest');
Route::get('dean/request','Dean@request');
Route::post('dean/create_request','Dean@Createrequest');
Route::get('dean/track_request','Dean@GetTrackingRequest');
Route::get('dean/dean/check_status','Dean@CheckedStatusRequest');
Route::get('dean/dean/check_status_request','Dean@CheckedStatusRequestData');



// President 

Route::get('president/request_data','SchoolPresident@GetData');
Route::get('president/get_request','SchoolPresident@GetRequest');
Route::post('president/approved_supplies','SchoolPresident@GetApprovedRequest');
Route::get('school_president/president/check_status_request','SchoolPresident@CheckedStatusRequestData');


// Finance 

Route::get('finance/request_data','Finance@GetData');
Route::get('finance/get_request','Finance@GetRequest');
Route::post('finance/approved_supplies','Finance@GetApprovedRequest');
Route::get('finance/finance/check_status_request','Finance@CheckedStatusRequestData');


// Admin


Route::get('admin/create_user','Admin@GetCreateUser');
Route::post('admin/create_users','Admin@GetCreateUsers');
Route::get('admin/reset_password','Admin@GetResetPassword');
Route::post('find_reset_password', 'Admin@find_reset_password');
Route::post('update_reset_password', 'Admin@update_reset_password');

