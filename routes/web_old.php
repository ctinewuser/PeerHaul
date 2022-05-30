<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


/*image upload */
use App\Http\Controllers\ImageUploadController;
 
Route::get('image-upload-preview', [ImageUploadController::class, 'index']);
Route::post('upload-image', [ImageUploadController::class, 'store']);
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

Route::get('/', function () {
    return view('login');
});

//=== Admin Login
Route::post('/admin_login', 'AdminController@login');
Route::get('/logout', 'AdminController@logout');
Route::get('/dashboard', 'AdminController@dashboard');
 
//=== User's Related
Route::get('/driver-list', 'AdminController@getAllUsers');
Route::get('/removeUser/{id}', 'AdminController@removeUser');
Route::get('/removeCustomer/{id}', 'AdminController@removeCustomer');
Route::post('/changeUserSt', 'AdminController@changeUserSt');

//////Added by naincy 14/12/21
Route::get('/customer-list', 'AdminController@getAllCustomer');
Route::get('/job-details', 'AdminController@getAlljobDetail');
Route::get('/vechicle-list', 'AdminController@getAllVechile');
Route::get('/viewUser/{id}', 'AdminController@getUserById');
Route::get('/viewCustomer/{id}', 'AdminController@getCustomerById');
Route::get('/viewJob/{id}', 'AdminController@getJobDetailById');
Route::get('/editContent/{id}', 'AdminController@editContentById');
Route::get('/review', 'AdminController@getReviewList');
Route::get('/vehicle-info', 'AdminController@getVehicleinfo');
Route::get('/terms-condition', 'AdminController@getTermsCondition');
Route::get('/popup-content-list', 'AdminController@getPopupContent');
Route::post('/update-content/{id}', 'AdminController@updateContent');
Route::post('/update-terms-condition', 'AdminController@updateTerms');
Route::post('/update-customer/{id}', 'AdminController@updateCustomer');
Route::get('/parcel-list', 'AdminController@parcelList');
Route::post('/image-upload' , 'AdminController@imageUpload') ;
Route::get('/deadline-List' , 'AdminController@deadlineList') ;
Route::get('/jobBid-List' , 'AdminController@getjobBidList') ;
