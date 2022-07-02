<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//header('Access-Control-Allow-Origin: *');
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

Route::middleware('auth:api')->get('/user', function (Request $request)
{
    return $request->user();
});
// Driver APIS


Route::post('sign-up-driver', 'DriverApiController@signUp');
Route::post('send-otp-driver', 'DriverApiController@send_otp');
Route::post('driver-login', 'DriverApiController@driver_login');
Route::post('update-driver-profile-image', 'DriverApiController@updateDriverProfileImage');
Route::post('get-driver-profile', 'DriverApiController@getDriverProfile');
Route::post('update-driver-profile', 'DriverApiController@updateProfile');
Route::post('update-vehicle-info', 'DriverApiController@uploadVehicleInfo');
Route::post('update-vehicle-info-test', 'DriverApiController@uploadVehicleInfotest');
Route::post('change-password', 'DriverApiController@change_password');
Route::post('place-bid', 'DriverApiController@place_bid');
Route::post('my-jobs', 'DriverApiController@myJobs');
Route::post('all-listing', 'DriverApiController@allListing');
Route::get('vehicle-type-list', 'DriverApiController@vehicle_type');
Route::post('single-bid-detail', 'DriverApiController@getBidDetails');
Route::post('my-account', 'DriverApiController@my_account');
Route::post('my-deliveries', 'DriverApiController@my_deliveries');
Route::post('accept-bid-details', 'DriverApiController@acceptBidDetails');
Route::post('forgot-password-driver', 'DriverApiController@forgotPassword');
Route::post('notification-list', 'DriverApiController@notificationList');
Route::post('completed-bid-list', 'DriverApiController@getCompletedBidList');
Route::post('express-job-details', 'DriverApiController@getExpressJobList');
Route::post('suggest-cost-job-details', 'DriverApiController@getSuggestedCostJobList');
Route::post('upload-product-image', 'DriverApiController@uploadProductImage');
Route::post('upload-dropoff-image', 'DriverApiController@uploadDropOffImage');
Route::post('add-account-detail', 'DriverApiController@addAccountDetail');
Route::post('transaction-history', 'DriverApiController@getTransactionDetail');
Route::post('complete-dropoff', 'DriverApiController@getCompleteDropOff');
Route::post('complete-pickup', 'DriverApiController@getCompletePickUp');
Route::post('find-distance', 'DriverApiController@distance');
Route::post('cancel-job', 'DriverApiController@cancelledJob');
Route::post('saved-card-list', 'DriverApiController@savedCardList');
Route::post('delete-card', 'DriverApiController@deleteCardDetail');
Route::post('add-card-detail', 'DriverApiController@addCardDetail');
Route::post('pickup-person-details', 'DriverApiController@getPickupDetail');

//Route::post('start-job', 'DriverApiController@startJob');
// customer APIs
Route::post('update-multiple-image-test', 'CustomerApiController@storeMultipleImagestest');
Route::post('sign-up-customer', 'CustomerApiController@signUp');
Route::post('customer-login', 'CustomerApiController@customer_login');
Route::post('update-customer-profile-image', 'CustomerApiController@updateProfileImage');
Route::post('update-multiple-image', 'CustomerApiController@storeMultipleImages');
Route::post('get-customer-profile', 'CustomerApiController@getCustomerProfile');
Route::post('update-customer-profile', 'CustomerApiController@updateProfile');
Route::post('change-password-customer', 'CustomerApiController@change_password');
Route::post('my-listing', 'CustomerApiController@myListing');
Route::post('customer-deliveries', 'CustomerApiController@myDeliveries');
Route::post('customer-deliveries-web', 'CustomerApiController@myDeliveriesweb');
Route::get('terms-condition', 'CustomerApiController@termsCondition');
Route::post('review-details', 'CustomerApiController@reviewDetails');
Route::post('store-review', 'CustomerApiController@storeReview');
Route::post('forgot-password-customer', 'CustomerApiController@forgotPassword');
Route::get('payment-terms-content', 'CustomerApiController@paymentTerms');
Route::post('notification-list', 'CustomerApiController@notificationList');
Route::post('accept-bid-bycustomer', 'CustomerApiController@acceptBidByCustomer');
Route::post('job-detail-by-jobid', 'CustomerApiController@getJobDetailByJobId');
Route::post('send-otp', 'CustomerApiController@send_otp');
Route::post('time-check', 'CustomerApiController@time_check');

// Listing APIs

Route::post('create-listing', 'ListingApiController@create_listing');
Route::post('edit-listing', 'ListingApiController@edit_listing');
Route::get('get-parcel-size', 'ListingApiController@get_parcel_size');
Route::post('get-deadline-byStatus', 'ListingApiController@getDeadlineByStatus');
Route::post('get-price-estimate', 'ListingApiController@getpriceEstimate');
Route::post('get-price-estimate-old', 'ListingApiController@getpriceEstimateold');
Route::post('get-template-detail', 'ListingApiController@getTemplateDetail');
Route::post('check-template', 'ListingApiController@checkTemplate');
Route::post('template-data-bytempid', 'ListingApiController@templateDataByTempId');
Route::post('delete-listing', 'ListingApiController@deleteListing');
Route::get('delete-image', 'ListingApiController@delImage');
Route::post('get-bid-list-byid', 'ListingApiController@getBidListById');
Route::post('store-listing', 'ListingApiController@storeListing');
Route::post('get-bid-list-byJobid', 'ListingApiController@getBidListByJobId');

//Route::post('create-listing' , 'ListingApiController@create_listing') ;
Route::post('add-item-information' , 'ListingApiController@add_item_information') ;
Route::post('add-pickup-contact' , 'ListingApiController@add_pickup_contact') ;
Route::post('add-delivery-details' , 'ListingApiController@add_delivery_details') ;
Route::post('add-time', 'CustomerApiController@addTime');
Route::post('delete-listing-byCustomerid', 'CustomerApiController@deleteListingByCustomerId');
