<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// bonus profile user
Route::post('profile', 'Api\ProfileController@index');

//TimeKeeping
Route::post('attendance', 'Api\QueueController@attendanceData');

//employee (inactive - active)
Route::get('employee', 'Api\EmployeeController@employee');
//employ update new user and finger
Route::post('employee', 'Api\EmployeeController@employeeUpdate');
//get finger employee
Route::get('finger', 'Api\FingerController@index');
Route::post('finger', 'Api\FingerController@update');
Route::get('user_finger', 'Api\UserFingerController@index');
Route::get('addtime', 'Api\AddTimeController@indexUser');
Route::get('addtime_finger', 'Api\AddTimeController@indexFinger');
Route::post('addtime', 'Api\AddTimeController@update');

Route::group(['prefix' => "data"], function () {
    Route::post('read-data', 'Api\PayrollGoogleSheetController@readData');
    Route::post('exportData', 'Api\PayrollGoogleSheetController@exportDataHrmToGoogleSheet');
    Route::post('clearData', 'Api\PayrollGoogleSheetController@clearDataFromGoogleSheet');

    Route::post('importData', 'Api\PayrollGoogleSheetController@getAllDataSheet');
});
