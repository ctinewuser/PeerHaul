<?php
namespace App\Http\Controllers;
use Helper; // Important
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Customer;
use App\Repository\DriverRepository ;
use App\Repository\CustomerRepository ;
use App\Repository\ListingRepository ;
use App\Repository\VehicleRepository ;
use App\Models\UserDriver;
use App\Models\Admin;
use App\Models\ParcelSize;
use App\Models\PriceEstimator;
use DB;
use Hash;
use Auth;
use File;
use Image;
use Mail;
use Lang;
use Session;
use DateTime;

class AdminFunctionsController extends Controller
 {
    
    public function __construct(DriverRepository $user_driver,Admin $admin , CustomerRepository $user_customer ,  ListingRepository $job_detail ,ListingRepository $getjobotherdetails  
    ,ListingRepository $vehicle_type ,ListingRepository $review ,ListingRepository $terms , ListingRepository $bidjob , ListingRepository $popup, VehicleRepository $vehicle, ParcelSize $parcel ,ListingRepository $deadline ,ListingRepository $listing)
    {
        $this->user_driver = $user_driver ;
        $this->admin = $admin ;
        $this->user_customer = $user_customer ;
        $this->job_detail = $job_detail  ;
        $this->getjobotherdetails = $getjobotherdetails  ;
        $this->vehicle_type = $vehicle_type;
        $this->review = $review;
        $this->vehicle = $vehicle;
        $this->terms = $terms;
        $this->popup = $popup;
        $this->parcel = $parcel;
        $this->deadline = $deadline;
        $this->bidjob = $bidjob;
        $this->listing = $listing;
    } 
    
   public function blockCustomer($id)
   {
      $updatecus = $this->user_customer->blockCustomer($id) ;
      
      if($updatecus){
            return redirect()->back()
               ->with('success_msg', 'Customer blocked updated Successfully!');
        } else {    
            return redirect()->back()
               ->with('error_msg', 'Data Not Updated!');
        }

   }
   
      
   public function blockDriver($id)
   {
      $updatecus = $this->user_driver->blockDriver($id) ;
      
      if($updatecus){
            return redirect()->back()
               ->with('success_msg', 'Driver blocked updated Successfully!');
        } else {    
            return redirect()->back()
               ->with('error_msg', 'Data Not Updated!');
        }

   }
   
   
}