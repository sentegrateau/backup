<?php


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

Route::get('get-package-with-devices', 'Api\PackageController@getPackageWithDevices');
Route::get('get-home-owners', 'Api\HomeOwnerController@getHomeOwner');
Route::post('save-home-owners', 'Api\HomeOwnerController@saveHomeOwner');
Route::resource("package", "Api\PackageController");
Route::resource("package", "Api\PackageController");
Route::resource("room", "Api\RoomController");
Route::resource("home-owner", "Api\HomeOwnerController");
Route::resource("partner", "Api\PartnerController");
Route::resource("package_room", "Api\PackageRoomController");
Route::post("device/savePackageRoom", "Api\DeviceController@savePackageRoom");
Route::resource("device", "Api\DeviceController");
Route::resource('order', 'Api\OrderController');
Route::resource('draft', 'Api\DraftController');
Route::post('save-quotation', 'Api\DraftController@saveQuotation');
Route::get('draft-items/{id}', 'Api\DraftController@getDraftItems');
Route::get('getStatesList/{id}', 'Api\CountriesController@getStatesList');
Route::get('order-items/{id}', 'Api\OrderController@getOrderItems');
Route::get('package-rooms/{id}/{draftId?}', "Api\PackageRoomController@packageRooms")->name('api.package-rooms');
Route::get('room-devices/{id}', "Api\PackageRoomController@roomDevices");
Route::get('minmax-qty', 'Api\PackageRoomController@minMaxQty');
Route::post('upload-image', 'Api\ImageUploadController@store');
Route::put('update-package-order', 'Api\PackageController@updateOrder');
Route::put('change-device-status/{id}', 'Api\DeviceController@changeStatus');
Route::post('send-email', 'Api\EmailController@index');
Route::post('find-or-create-user', 'Api\UserController@findOrCreateUser');
Route::post('change-device-image/{id}', 'Api\DeviceController@changeImage');
Route::post('cart', 'Api\CartController@cart');
Route::post('get-shipping', 'Api\UserController@getShipping');
Route::get('getGstSetting', 'Api\CartController@getGstSetting');
Route::any('updatePaymentStatus', 'Api\OrderController@updatePaymentStatus');
Route::post('order/update-profile', 'Api\OrderController@updateProfile');
Route::post('order/update-shipping', 'Api\OrderController@updateShipping');
Route::post('user/get-user', 'Api\UserController@getUser');
//Webhook URL For Stripe
Route::any('stripe_payment', 'Api\OrderController@stripe_webhook');
Route::any('getShippingAddress', 'Api\OrderController@getShippingAddress');
Route::any('getCouponDetails', 'Admin\CouponController@getCouponDetails');

Route::post('delete-order', 'Api\OrderController@deleteOrder');
Route::post('order-paypal-success', 'Api\OrderController@paypalSuccess');


//Payment Routes
Route::post('charge', 'Api\PaymentController@charge');
Route::post('user/uploadBankTransferFile', 'Api\UserController@uploadBankTransferFile')->name('user.uploadBankTransferFile');

Route::get('get-invalid-token-string', 'Api\UserController@getInvalidTokenString');

Route::get('customization-rooms/{orderId}', 'Api\CustomizationController@getRoomsWithOrder')->name('custom.getRoomsWithOrder');
Route::get('customization-devices/{orderId}/{roomId}', 'Api\CustomizationController@getDevicesWithOrder')->name('custom.getDevicesWithOrder');
