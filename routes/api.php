<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageRoomController;
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

Route::resource("package", "App\Http\Controllers\PackageController");
Route::resource("room", "App\Http\Controllers\RoomController");
Route::resource("partner", "App\Http\Controllers\PartnerController");
Route::resource("package_room", "App\Http\Controllers\PackageRoomController");
Route::resource("device", "App\Http\Controllers\DeviceController");
Route::resource('order', 'App\Http\Controllers\OrderController');
Route::resource('draft', 'App\Http\Controllers\DraftController');
Route::post('save-quotation', 'App\Http\Controllers\DraftController@saveQuotation');
Route::get('draft-items/{id}', 'App\Http\Controllers\DraftController@getDraftItems');

Route::get('order-items/{id}', 'App\Http\Controllers\OrderController@getOrderItems');
Route::get('package-rooms/{id}',"App\Http\Controllers\PackageRoomController@packageRooms");
Route::get('room-devices/{id}',"App\Http\Controllers\PackageRoomController@roomDevices");
Route::get('minmax-qty','App\Http\Controllers\PackageRoomController@minMaxQty');
Route::post('upload-image','App\Http\Controllers\ImageUploadController@store');
Route::put('update-package-order', 'App\Http\Controllers\PackageController@updateOrder');
Route::put('change-device-status/{id}', 'App\Http\Controllers\DeviceController@changeStatus');
Route::post('send-email', 'App\Http\Controllers\EmailController@index');
Route::post('find-or-create-user', 'App\Http\Controllers\UserController@findOrCreateUser');
Route::post('change-device-image/{id}', 'App\Http\Controllers\DeviceController@changeImage');

//Payment Routes
Route::post('charge', 'App\Http\Controllers\PaymentController@charge');
