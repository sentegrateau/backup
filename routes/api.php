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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::post("/package", [PackageController::class, 'store']);
// Route::get("/package", [PackageController::class, 'index']);


Route::resource("package", "App\Http\Controllers\PackageController");
// Route::resource("package", "App\Http\Controllers\PackageController");
Route::resource("room", "App\Http\Controllers\RoomController");
Route::resource("partner", "App\Http\Controllers\PartnerController");
Route::resource("package_room", "App\Http\Controllers\PackageRoomController");
Route::resource("device", "App\Http\Controllers\DeviceController");
Route::get('package-rooms/{id}',"App\Http\Controllers\PackageRoomController@packageRooms");
Route::get('room-devices/{id}',"App\Http\Controllers\PackageRoomController@roomDevices");
