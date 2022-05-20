<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Auth::routes(['verify' => true]);

Route::get('verify-other-user/{id}', 'Front\UserController@verifyOtherUser')->name('verification.otherUser');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');
Route::get('/customization/{orderId}', 'HomeController@customization');
Route::get('/get-captcha-code', 'HomeController@getCaptchaCode')->name('home.getCaptchaCode');
Route::post('/save-captcha-code', 'Front\PageController@saveContactForm')->name('page.saveContactForm');
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    // return what you want
    die('cleared');
});

Route::get('/home', 'HomeController@index')->name('home');


/*blog routes*/
Route::get('/blogs', 'Front\BlogController@index')->name('blogs.listing');
Route::get('/blogs/{slug}', 'Front\BlogController@blogDetail')->name('blogs.detail');

/*case study front routes*/
Route::get('/case-study', 'Front\CaseStudyController@index')->name('case_study.listing');
Route::get('/case-study/{slug}', 'Front\CaseStudyController@detail')->name('case_study.detail');
/*case study front routes*/

/*Order Thank you Page*/
Route::get('/thank-you/{order_id}', 'Front\UserController@orderThankYou')->name('user.thankYou');
Route::get('/user/invoice-download/{order_id}', 'Front\UserController@invoiceDownload')->name('user.invoiceDownload');
Route::get('/invoice-view/{order_id}', 'Front\UserController@invoiceview');
/*End Thank you page*/


Route::group(['middleware' => 'auth'], function () {
    /*suppport ticket routes*/
    Route::get('/support-ticket', 'Front\SupportTicketController@index')->name('support.ticket');
    Route::post('/support-ticket/store', 'Front\SupportTicketController@store')->name('support.ticket.store');
    Route::get('/support-ticket/listing', 'Front\SupportTicketController@listing')->name('support.ticket.listing');
    Route::get('support-ticket/close/{id}', 'Front\SupportTicketController@closeTicket')->name('support-ticket.close');
    /*suppport ticket routes*/

    /*suppport ticket comments routes*/
    Route::get('/support-ticket/{slug}', 'Front\SupportTicketCommentController@index')->name('support.ticket.comment');
    Route::post('/support-ticket-comment-store/{slug}', 'Front\SupportTicketCommentController@store')->name('support.ticket.comment.store');
    /*suppport ticket comments routes*/

    /*profile routes*/
    Route::get('/user/profile', 'Front\UserController@index')->name('user.profile');
    Route::get('/user/my-orders', 'Front\UserController@myOrder')->name('user.myOrder');
	Route::get('/user/orderstatus/{id}', 'Front\UserController@orderstatus')->name('front.orderstatus');
    //Route::get('/user/invoice-download/{order_id}', 'Front\UserController@invoiceDownload')->name('user.invoiceDownload');
    Route::get('user/bankImgRemove/{order_id}', 'Front\UserController@bankImgRemove')->name('user.bankImgRemove');
    Route::post('/user/profile/store', 'Front\UserController@store')->name('user.profile.store');
    Route::post('/user/profile/address/store', 'Front\UserController@storeAddress')->name('user.profile.address.store');
    /*profile routes*/


});


Route::prefix('admin')->group(function () {

    Route::get('/', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', 'HomeController@logout');
    Route::get('/quote', 'HomeController@quote')->name('quote');



});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::get('/dashboard', 'AdminController@index')->name('admin.home');
    Route::post('/logout', 'AdminController@logout')->name('admin.logout.submit');

});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::post('ckeditor/upload', 'Admin\CkeditorController@upload')->name('ckeditor.upload');


    Route::delete('blog/deleteAll', 'Admin\BlogController@deleteAll')->name('blog.deleteAll');
    Route::get('blog/delete/{id}', 'Admin\BlogController@delete')->name('blog.delete');
    Route::post('blog/image-delete', 'Admin\BlogController@deleteImage')->name('blog.blogImgDelete');
    Route::resource('blog', 'Admin\BlogController');

    /*Case study Routes*/
    Route::delete('case_study/deleteAll', 'Admin\CaseStudyController@deleteAll')->name('case_study.deleteAll');
    Route::get('case_study/delete/{id}', 'Admin\CaseStudyController@delete')->name('case_study.delete');
    Route::post('case_study/image-delete', 'Admin\CaseStudyController@deleteImage')->name('case_study.ImgDelete');
    Route::resource('case_study', 'Admin\CaseStudyController');
    /*Case study Routes*/



    Route::delete('blog-category/deleteAll', 'Admin\BlogCategoryController@deleteAll')->name('blog-category.deleteAll');
    Route::get('blog-category/delete/{id}', 'Admin\BlogCategoryController@delete')->name('blog-category.delete');
    Route::get('blog-category/updateFeatured/{id}', 'Admin\BlogCategoryController@updateFeatured')->name('blog-category.updateFeatured');
    Route::get('blog-category/activeDeactivate/{id}', 'Admin\BlogCategoryController@activeDeactivate')->name('blog-category.activeDeactivate');
    Route::resource('blog-category', 'Admin\BlogCategoryController');

    Route::get('blog-tag/activeDeactivate/{id}', 'Admin\BlogTagController@activeDeactivate')->name('blog-tag.activeDeactivate');
    Route::get('blog-tag/delete/{id}', 'Admin\BlogTagController@delete')->name('blog-tag.delete');
    Route::resource('blog-tag', 'Admin\BlogTagController');


    Route::delete('page/deleteAll', 'Admin\PageController@deleteAll')->name('page.deleteAll');
    Route::get('page/delete/{id}', 'Admin\PageController@delete')->name('page.delete');
    Route::resource('page', 'Admin\PageController');

    Route::delete('coupon/deleteAll', 'Admin\CouponController@deleteAll')->name('coupon.deleteAll');
    Route::get('coupon/delete/{id}', 'Admin\CouponController@delete')->name('coupon.delete');
    Route::get('coupon/edit/{id}', 'Admin\CouponController@edit')->name('coupon.edit');
    Route::resource('coupon', 'Admin\CouponController');

    Route::delete('video/deleteAll', 'Admin\VideoController@deleteAll')->name('video.deleteAll');
    Route::get('video/delete/{id}', 'Admin\VideoController@delete')->name('video.delete');
    Route::resource('video', 'Admin\VideoController');

    Route::delete('packages/deleteAll', 'Admin\PageController@deleteAll')->name('packages.deleteAll');
    Route::get('packages/delete/{id}', 'Admin\PageController@delete')->name('packages.delete');
    Route::resource('packages', 'Admin\PageController');

    Route::delete('drafts/deleteAll', 'Admin\DraftController@deleteAll')->name('drafts.deleteAll');
    Route::get('drafts/delete/{id}', 'Admin\DraftController@delete')->name('drafts.delete');
    Route::get('drafts/delete-item/{id}', 'Admin\DraftController@deleteItem')->name('drafts.deleteItem');
    Route::post('drafts/save-item', 'Admin\DraftController@saveItemQty')->name('drafts.saveItemQty');


    Route::resource('drafts', 'Admin\DraftController');

    /*Package Routes*/
    Route::get('package/order', 'Admin\PackageController@order')->name('package.order');
    Route::post('package/order', 'Admin\PackageController@orderSave')->name('package.orderSave');
    Route::get('package/delete/{id}', 'Admin\PackageController@delete')->name('package.delete');
    Route::get('package/activeDeactivate/{id}', 'Admin\PackageController@activeDeactivate')->name('package.activeDeactivate');
    Route::resource('package', 'Admin\PackageController');
    /*Package Routes*/

    /*Room Routes*/
    Route::get('room/delete/{id}', 'Admin\RoomController@delete')->name('room.delete');
    Route::get('room/activeDeactivate/{id}/{field_name}', 'Admin\RoomController@activeDeactivate')->name('room.activeDeactivate');
    Route::resource('room', 'Admin\RoomController');
    /*Room Routes*/

    /*Home Owner Settings Routes*/
    Route::get('home-owner-settings/relations', 'Admin\HomeOwnerController@relations')->name('home-owner-settings.relations');
    Route::get('home-owner-settings/delete/{id}', 'Admin\HomeOwnerController@delete')->name('home-owner-settings.delete');
    Route::get('home-owner-settings/activeDeactivate/{id}/{field_name}', 'Admin\HomeOwnerController@activeDeactivate')->name('home-owner-settings.activeDeactivate');
    Route::resource('home-owner-settings', 'Admin\HomeOwnerController');
    /*Home Owner Settings Routes*/

    /*Devices Routes*/
    Route::delete('device/deleteAll', 'Admin\DeviceController@deleteAll')->name('device.deleteAll');
    Route::get('device/delete/{id}', 'Admin\DeviceController@delete')->name('device.delete');
    Route::get('device/add-package/{device_id}', 'Admin\DeviceController@addPackage')->name('device.addPackage');
    Route::post('device/getMaxMinQty', 'Admin\DeviceController@getMaxMinQty')->name('device.getMaxMinQty');
    Route::post('device/save-packages/{device_id}', 'Admin\DeviceController@saveCombination')->name('device.saveCombination');
    Route::get('device/activeDeactivate/{id}', 'Admin\DeviceController@activeDeactivate')->name('device.activeDeactivate');
    Route::resource('device', 'Admin\DeviceController');
    /*Room Routes*/

    /*Make Package*/
    Route::delete('make-packages/deleteAll', 'Admin\MakePackageController@deleteAll')->name('make-packages.deleteAll');
    Route::get('make-packages/delete/{id}', 'Admin\MakePackageController@delete')->name('make-packages.delete');
    Route::get('make-packages/makeKit/{id}', 'Admin\MakePackageController@makeKit')->name('make-packages.makeKit');
    Route::post('make-packages/save-order/{id}', 'Admin\MakePackageController@order')->name('make-packages.order');

    Route::resource('make-packages', 'Admin\MakePackageController');

    /*Orders Routes*/
    Route::get('orders/delete/{id}', 'Admin\OrderController@delete')->name('orders.delete');
    Route::delete('orders/deleteAll', 'Admin\OrderController@deleteAll')->name('orders.deleteAll');
    Route::get('orders/activeDeactivate/{id}', 'Admin\OrderController@activeDeactivate')->name('orders.activeDeactivate');
    Route::post('orders/saveOtherDetails/{id}', 'Admin\OrderController@saveOtherDetails')->name('orders.saveOtherDetails');

    Route::resource('orders', 'Admin\OrderController');
    //Route::get('order_email/{id}', 'Admin\OrderController@orderEmail')->name('orders.email.setting');
    Route::get('order_email_all/{id}', 'Admin\OrderController@orderEmail')->name('orders.email.setting');
    Route::post('order_email/submit/{id}', 'Admin\OrderController@orderEmailSubmit')->name('orders.email.submit');


    /*Orders Routes*/

    Route::get('banner/delete/{id}', 'Admin\BannerController@delete')->name('banner.delete');
    Route::delete('banner/deleteAll', 'Admin\BannerController@deleteAll')->name('banner.deleteAll');
    Route::resource('banner', 'Admin\BannerController');


    Route::get('customers/activeDeactivate/{id}', 'Admin\CustomerController@activeDeactivate')->name('customers.activeDeactivate');
    Route::delete('customers/deleteAll', 'Admin\CustomerController@deleteAll')->name('customers.deleteAll');
    Route::get('customers/delete/{id}', 'Admin\CustomerController@delete')->name('customers.delete');
    Route::get('customers/password-reset/{id}', 'Admin\CustomerController@passwordReset')->name('customers.reset');
    Route::post('customers/password-reset/{id}', 'Admin\CustomerController@passwordResetPost')->name('customers.resetPost');
    Route::resource('customers', 'Admin\CustomerController');

    Route::post('settings/save', 'Admin\SettingController@save')->name('settings.save');
    Route::resource('settings', 'Admin\SettingController');

    //support ticket category
    Route::get('ticket-category/delete/{id}', 'Admin\TicketCategoryController@delete')->name('ticket-category.delete');
    Route::get('ticket-category/updateFeatured/{id}', 'Admin\TicketCategoryController@updateFeatured')->name('blog-ticket.updateFeatured');
    Route::get('ticket-category/activeDeactivate/{id}', 'Admin\TicketCategoryController@activeDeactivate')->name('ticket-category.activeDeactivate');
    Route::resource('ticket-category', 'Admin\TicketCategoryController');

    //support tickets
    Route::resource('support-ticket', 'Admin\SupportTicketController');
    Route::get('support-ticket/activeDeactivate/{id}', 'Admin\SupportTicketController@activeDeactivate')->name('support-ticket.activeDeactivate');
    Route::get('support-ticket-comment/{id}', 'Admin\SupportTicketController@comments')->name('support-ticket.comments');
    Route::post('support-ticket-comment-store/{id}', 'Admin\SupportTicketController@commentStore')->name('support-ticket.comments.store');
    Route::get('support-ticket/delete/{id}', 'Admin\SupportTicketController@delete')->name('support-ticket.delete');
    //Admin User
    Route::get('admin-user/permission/{id}', 'Admin\AdminUserController@assignPermission')->name('admin-user.assignPermission');
    Route::post('admin-user/permission/{id}', 'Admin\AdminUserController@saveAssignPermission')->name('admin-user.saveAssignPermission');
    Route::get('admin-user/activeDeactivate/{id}', 'Admin\AdminUserController@activeDeactivate')->name('admin-user.activeDeactivate');
    Route::delete('admin-user/deleteAll', 'Admin\AdminUserController@deleteAll')->name('admin-user.deleteAll');
    Route::get('admin-user/delete/{id}', 'Admin\AdminUserController@delete')->name('admin-user.delete');
    Route::resource('admin-user', 'Admin\AdminUserController');

    /*Orders Routes*/
    Route::get('home-owner-quotes/delete/{id}', 'Admin\HomeOwnerQuoteController@delete')->name('home-owner-quotes.delete');
    Route::delete('home-owner-quotes/deleteAll', 'Admin\HomeOwnerQuoteController@deleteAll')->name('home-owner-quotes.deleteAll');
    Route::resource('home-owner-quotes', 'Admin\HomeOwnerQuoteController');
    /*Orders Routes*/

    Route::delete('contact-us/deleteAll', 'Admin\ContactUsController@deleteAll')->name('contact-us.deleteAll');
    Route::get('contact-us/delete/{id}', 'Admin\ContactUsController@delete')->name('contact-us.delete');
    Route::resource('contact-us', 'Admin\ContactUsController');

    Route::delete('sub-devices/deleteAll', 'Admin\SubDeviceController@deleteAll')->name('sub-devices.deleteAll');
    Route::get('sub-devices/delete/{id}', 'Admin\SubDeviceController@delete')->name('sub-devices.delete');
    Route::resource('sub-devices', 'Admin\SubDeviceController');

    Route::delete('device-parameters/deleteAll', 'Admin\DeviceParameterController@deleteAll')->name('device-parameters.deleteAll');
    Route::get('device-parameters/delete/{id}', 'Admin\DeviceParameterController@delete')->name('device-parameters.delete');
    Route::resource('device-parameters', 'Admin\DeviceParameterController');

    /*scenes routes*/
    Route::get('scene/{type}', 'Admin\SceneController@index')->name('scene.index');
    Route::post('scene/add/{type}', 'Admin\SceneController@store')->name('scene.add');
    /*scenes routes*/
});

Route::get('/{page}', 'Front\PageController@index')->name('page.show');
