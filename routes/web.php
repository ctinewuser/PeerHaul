<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminFunctionsController;

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

Route::get('/adminLogin', 'AdminController@dashboard');
Route::post('/login_admin', 'AdminController@login_admin');

//=== Admin Login
Route::post('/admin_login', 'AdminController@login');
Route::get('/logout', 'AdminController@logout');
Route::get('/dashboard', 'AdminController@dashboard');
 
//=== User's Related
Route::get('/driver-list', 'AdminController@getAllUsers');
Route::get('/removeUser/{id}', 'AdminController@removeUser');
Route::get('/removeDeadline/{id}', 'AdminController@removeDeadline');

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
Route::get('/editDeadline/{id}', 'AdminController@editDeadlineById');
Route::get('/editFees/{id}', 'AdminController@editFeesById');
Route::post('/update-deadline/{id}', 'AdminController@updateDeadline');
Route::post('/update-fees/{id}', 'AdminController@updateFees');
Route::get('/review', 'AdminController@getReviewList');
Route::get('/vehicle-info', 'AdminController@getVehicleinfo');
Route::get('/terms-condition', 'AdminController@getTermsCondition');
Route::get('/popup-content-list', 'AdminController@getPopupContent');
Route::post('/update-content/{id}', 'AdminController@updateContent');
Route::post('/update-terms-condition', 'AdminController@updateTerms');
Route::post('/update-customer/{id}', 'AdminController@updateCustomer');
Route::get('/parcel-list', 'AdminController@parcelList');
Route::get('/fees-list', 'AdminController@feesStructure');
Route::get('/transaction-list', 'AdminController@transactionList');

Route::post('/image-upload' , 'AdminController@imageUpload') ;
Route::get('/deadline-List' , 'AdminController@deadlineList') ;
Route::get('/jobBid-List/{id}' , 'AdminController@getjobBidList') ;

//============= 21-05-2022
Route::get('/viewReviews/{id}', 'AdminController@viewReviewsOfDriver') ;
Route::get('/blockCustomer/{id}', 'AdminFunctionsController@blockCustomer') ;
Route::get('/blockDriver/{id}', 'AdminFunctionsController@blockDriver') ; 
Route::get('/blockJob/{id}', 'AdminController@blockJob') ;  