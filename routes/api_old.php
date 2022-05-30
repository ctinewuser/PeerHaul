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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Driver APIS
 
Route::post('sign-up-driver', 'DriverApiController@signUp') ;
Route::post('driver-login', 'DriverApiController@driver_login') ;
Route::post('update-profile-image' , 'DriverApiController@updateProfileImage') ;
Route::post('get-driver-profile' , 'DriverApiController@getDriverProfile') ;
Route::post('update-driver-profile' , 'DriverApiController@updateProfile') ;
Route::post('update-vehicle-info' , 'DriverApiController@uploadVehicleInfo') ;
Route::post('reset-password' , 'DriverApiController@reset_password') ;
Route::post('place-bid' , 'DriverApiController@place_bid') ;
Route::post('my-jobs' , 'DriverApiController@myJobs') ;
Route::get('vehicle-type-list' , 'DriverApiController@vehicle_type') ;
Route::post('single-bid-detail' , 'DriverApiController@getBidDetails') ;
Route::post('my-account' , 'DriverApiController@my_account') ;
Route::post('my-deliveries' , 'DriverApiController@my_deliveries') ;
Route::post('accept-bid-details' , 'DriverApiController@acceptBidDetails') ;



// customer APIs
Route::post('sign-up-customer', 'CustomerApiController@signUp') ;
Route::post('customer-login', 'CustomerApiController@customer_login') ;
Route::post('update-customer-profile-image' , 'CustomerApiController@updateProfileImage') ;
Route::post('update-multiple-image' , 'CustomerApiController@store') ;
Route::post('get-customer-profile' , 'CustomerApiController@getCustomerProfile') ;
Route::post('update-customer-profile' , 'CustomerApiController@updateProfile') ;
Route::post('reset-password-customer' , 'CustomerApiController@reset_password') ;
Route::post('my-listing' , 'CustomerApiController@myListing') ;
Route::post('customer-deliveries' , 'CustomerApiController@myDeliveries') ;
Route::get('terms-condition' , 'CustomerApiController@termsCondition') ;
Route::post('review-details' , 'CustomerApiController@reviewDetails') ;
Route::post('store-review' , 'CustomerApiController@storeReview') ;
Route::get('forgot-password-customer' , 'CustomerApiController@forgotPassword') ; // Incomplete
Route::get('payment-terms-content' , 'CustomerApiController@paymentTerms') ;
Route::post('notification-list' , 'CustomerApiController@notificationList') ;
Route::post('accept-bid-bycustomer' , 'CustomerApiController@acceptBidByCustomer') ;


// Listing APIs
Route::post('create-listing' , 'ListingApiController@create_listing') ;
Route::post('edit-listing' , 'ListingApiController@edit_listing') ;
Route::post('add-item-information' , 'ListingApiController@add_item_information') ;
Route::post('add-pickup-contact' , 'ListingApiController@add_pickup_contact') ;
Route::post('add-delivery-details' , 'ListingApiController@add_delivery_details') ;
Route::get('get-parcel-size' , 'ListingApiController@get_parcel_size') ;
Route::post('get-deadline-byStatus' , 'ListingApiController@getDeadlineByStatus') ;
Route::post('get-price-estimate' , 'ListingApiController@getpriceEstimate') ;
Route::post('get-template-detail' , 'ListingApiController@getTemplateDetail') ;
Route::post('check-template' , 'ListingApiController@checkTemplate') ;
Route::post('template-data-bytempid' , 'ListingApiController@templateDataByTempId') ;
Route::post('delete-listing' , 'ListingApiController@deleteListing') ;
Route::post('store-listing' , 'ListingApiController@storeListing') ;


