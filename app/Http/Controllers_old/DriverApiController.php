<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repository\DriverRepository;
use App\Repository\VehicleRepository;
use App\Repository\ListingRepository;
use App\Repository\BidRepository;
use App\Helpers\CommonHelper;
use DB;
use Hash;
use Auth;
use File;
use Image;
use Mail;
use Lang;
use Session;
use DateTime;
use URL;

class DriverApiController extends Controller
 {

   public function __construct(DriverRepository $user_driver, VehicleRepository $vehicle, BidRepository $bid , ListingRepository $listing)
    {
       $this->user_driver = $user_driver ;
       $this->vehicle = $vehicle ; 
       $this->bid = $bid ;       
        $this->listing = $listing ;  
    } 

    public function signUp(Request $request){

      $post = $request->all();
           
        if(!empty($post)) {
           
          // check Email & Phone

           $email = $post['email'] ; $phone = $post['phone'] ;
 
           $checkEmail = $this->user_driver->checkEmail($email) ; 
           $checkPhone = $this->user_driver->checkPhone($phone) ; 

           if($checkEmail) 
             {
               return response()->json(['success' => 500,  'message' => 'Email ID Already exist']); 
             }

           if($checkPhone) 
             { 
               return response()->json(['success' => 500,  'message' => 'Phone Number Already exist']); 
             }

            $userCreate = $this->user_driver->store($request);

             if(!empty($userCreate)) {
                
              DB::commit();

                $userDetails = $this->user_driver->checkEmail( $email ) ;

                $getDetails['id'] = $userDetails->id ;
                $getDetails['name'] = $userDetails->name ;
                $getDetails['email'] = $userDetails->email ;
                $getDetails['phone'] = $userDetails->phone ;
                $getDetails['profile_img'] = URL::to('/')."/public/uploads/profile_image/default.png";
                $getDetails['fcmToken'] = $userDetails->fcmToken ;
                $getDetails['my_referral_code'] = $userDetails->my_referral_code ;

                return response()->json(['success' => 200, 'message' => 'Profile Created Successfully.', 'user Details'=>$getDetails]);

            }else{

                DB::rollback();
                return response()->json(['success' => 500,  'message' => 'Profile Not Created']);
            }
           } else {

                DB::rollback();
                return response()->json(['success' => 500, 'message' => 'All Parameters Required']);
        }
    }
     
    public function driver_login(Request $request)
    {
      
      try {

         $post = $request->all() ;
 
         // check Email 
         $checkEmail = $this->user_driver->checkEmail($post['email']) ;  
          if($checkEmail) 
           {
             
            $userCheck = $this->user_driver->driverLogin($request) ;
            $fcmToken = $post['fcmToken'] ;

            if(!empty($userCheck))
             {
                $update = $this->user_driver->updateFCM($userCheck['id'],$fcmToken) ;               
                $userDetails = $this->user_driver->checkEmail($post['email']) ;

                    $getDetails['id'] = $userDetails->id ;
                    $getDetails['username'] = $userDetails->name ;
                    $getDetails['email'] = $userDetails->email ;
                    $getDetails['phone'] = $userDetails->phone ;
                    if($userDetails->profile_img != ' ')
                     {
                        $getDetails['profile_img'] = URL::to('/')."/public/uploads/profile_image/".$userDetails->profile_img ;
                     }
                      else
                     {
                       $getDetails['profile_img'] = URL::to('/')."/public/uploads/profile_image/default.png";
                     }
                  //$getDetails['profile_img'] = $userDetails->profile_img ;
                    $getDetails['fcmToken'] = $userDetails->fcmToken ;

                 return response()->json(['success' => 200, 'message' => 'Login successfully.', 'user_details'=>$getDetails ]) ;

              } else {

                DB::rollback();
                return response()->json(['success' => 500, 'message' => 'Password does not match']) ;
             }
            } else {
             return response()->json(['success' => 500, 'message' => 'Email Id not Exist']) ;
           }

          } catch (\Exception $e) {
             DB::rollback();
             return response()->json(['success' => 500, 'message' => 'All Parameter Required']);
        }
    }

    /**
     * Update profile
     * @param object
     */
  ////////////My Account Api created by Naincy 13/04/2022
   
     public function forgetPassword()
     {
        
    }
 
   public function my_account(Request $request)
   {
   
     $driverId = $request['driver_id'] ; 
     if($driverId != '')
     {
            $userDetails = $this->user_driver->getDriverById($driverId); 
            if (!empty($userDetails))
             {
                $getDetails['name'] = $userDetails->name ;
              $getDetails['profile_img'] = $userDetails->profile_img ;  
              $getDetails['account'] = "123456" ; 
               $getDetails['earning_this_year'] = "$20,000.00" ; 
                $getDetails['current_month'] = "$5000" ; 

             }
              return response()->json(['success' => 200, 'message' => 'Success', 'user_details'=>$getDetails ]);
     } else {

          return json_encode(array('success' => 500, 'message' => 'Driver ID is Required')) ;
       } 

   }
    public function acceptBidDetails(Request $request)
    {
       $driverId = $request['driver_id'];
       $detail = $this->bid->getAcceptBidDetail($driverId) ;
      if(!empty($detail))
      {
          return response()->json(['success' => 200, 'message' => 'Success', 'list'=>$detail]);
        }
    else
    {
        DB::rollback();
            return response()->json(['success' => 500,  'message' => 'Driver ID not exist']) ; 
      }
    }
      
    public function my_deliveries(Request $request)
    {
         $driverId = $request['driver_id'] ; 
         $filter_type = $request['filter_type'] ; ////1 jobs , 2 bids , 3 completed
         $search_jobs = $request['status'] ; 
         if($driverId != '')
         {
            $delivery = $this->listing->getAllJobsByDriverId($driverId);
            
             if (!empty($delivery))
             {
                $getDetails['name'] = $delivery->name ;
                  $getDetails['pick_up_location'] = $delivery->pick_up_location ;
                  $getDetails['drop_off_location'] = $delivery->drop_off_location ;
                  $getDetails['descriptive_title'] = $delivery->descriptive_title ;
                  $getDetails['private_information'] = $delivery->private_information ;
             }
          
           return response()->json(['success' => 200, 'message' => 'Success', 'job_details'=>$getDetails ]);
         }else{
            return json_encode(array('success' => 500, 'message' => 'Driver ID is Required')) ;
         }
    }

    public function updateProfile(Request $request)
     {

        $driverId = $request['driver_id'] ;

         if($driverId != '') {
         
          try {
           
            $update = $this->user_driver->updateDriverProfile($request);
 
            if ($update) {

               return json_encode(array('success' => 200, 'message' => 'Successfully Updated' )) ;
           
             }

               return json_encode(array('success' => 500, 'message' => 'Not Updated' )) ;
        
        } catch (\Exception $e) {

            return json_encode(array('success' => 500, 'message' => 'All Parameter Required')) ;

        }

       } else {

          return json_encode(array('success' => 500, 'message' => 'Driver ID is Required')) ;
       } 
    }
    
    public function getDriverProfile(Request $request){
      
      $usrId = $request['driver_id'] ; 
       
        if($usrId != '')
          {
           $userDetails = $this->user_driver->getDriverById($usrId) ;

            if($userDetails)
              {

                    $getDetails['id'] = $userDetails->id ;
                    $getDetails['username'] = $userDetails->name ;
                    $getDetails['email'] = $userDetails->email ;
                    $getDetails['phone'] = $userDetails->phone ;

                    if($userDetails->profile_img != ''){
                       $getDetails['profile_img'] = 'http://localhost/peer_haul/public/uploads/profile_image/'.$userDetails->profile_img ;
                     } else {
                       $getDetails['profile_img'] = '' ;
                    }
                    
                return response()->json(['success' => 200, 'message' => 'Success', 'user_details'=>$getDetails ]);
            } else {
                return json_encode(array('success' => 500, 'message' => 'Data Not Found'));
            }

        } else {
            return json_encode(array('success' => 500, 'message' => 'All Parameter Required'));
       }
    }

  

    public function reset_password(Request $request){
      $driverId = $request['driver_id'] ; 
      $password = $request['password'] ; 
      if($driverId != '' && $password != '' ) {

        $post  = $request->all();  
        $userr = $this->user_driver->getDriverById($driverId) ;
       
         if($userr) {

          /* // check old password
           $checkPass = $this->user_driver->where('password',md5($post['old_password']))->where('id',$usrId)->first();
           if(!$checkPass) { 
               return response()->json(['success' => 500,'message' =>'Old Password not Match']); 
            } */

           if($password != ''){

             $update = $this->user_driver->updatePasssword($driverId,$password) ;
             
           }       

          if (!$update) {
            return json_encode(array('success' => 500, 'message' => 'Not Updated' ));
          }
            return json_encode(array('success' => 200, 'message' => 'Password Changed Successfully'));

         } else {
          
           return json_encode(array('success' => 500, 'message' => 'User not Found'));
        } 

     } else {

        return json_encode(array('success' => 500, 'message' => 'All Parameter Required : driver_id, password'));
    }
  } 
 
  public function updateProfileImage(Request $request)
      {
        if(!empty($_POST)) {

        try {
            $userr = $this->user_driver->getDriverById($request['driver_id']);
                   
            if ($request['profile_image']) {

              if ($request->hasFile('profile_image'))
               {
                  $image = $request->file('profile_image');
                  $img = time() . '.' . $image->getClientOriginalExtension();
                  $destinationPath = public_path('/uploads/profile_image/');
                  $image->move($destinationPath, $img);
               }
              $userr->profile_img = $img ;
            }  

            $userr->save();
 
            if (!$userr) {

                return json_encode(array('success' => 500, 'message' => 'Not Updated' ));
            }
            
            return json_encode(array('success' => 200, 'message' => 'Successfully Updated', 'image'=>$img ));

        } catch (\Exception $e) {

            return json_encode(array('success' => 500, 'message' => 'All Parameter Required'));

        }
       } else {
          return json_encode(array('success' => 500, 'message' => 'All Parameter Required'));
      }
    }

    public function uploadVehicleInfo(Request $request)
    {
       
      $post = $request->all() ;
           
        if(!empty($post)) {
          $check_Vinfo = $this->vehicle->getVehicleById($request['driver_id']);

          if(!$check_Vinfo)
          {
             $userV_info = $this->vehicle->storeVehicleInfo($request) ;
          }
          else
          {
            $userV_info = $this->vehicle->updateVehicleInfo($request) ;
          } 

           if(!empty($userV_info)) {
              
            return response()->json(['success' => 200, 'message' => 'Vehicle information saved successfully.']) ;

           } else {

              DB::rollback() ;
              return response()->json(['success' => 500,  'message' => 'Vehicle information not saved']) ;
           }

         } else {

              DB::rollback() ;
              return response()->json(['success' => 500, 'message' => 'All Parameters Required']) ;
        }

    }  

    public function vehicle_type(Request $request)
    {
       $vehicle_type = $this->vehicle->vehicle_type();
       return response()->json(['success' => 200, 'message' => 'Vehicle type list .', 'vehicle-type-list' => $vehicle_type ]) ;
    }
 
    public function notificationCount(Request $request){

    $userId = $request['user_id'] ;
    $notificationList = $this->notifications->where('to_user_id',$userId)->where('readStatus','0')->get();

    if(sizeof($notificationList)>0){
      return json_encode(array('success' => 200, 'message' => 'Success', 'notification_count'=>count($notificationList)));
    } else {
      return json_encode(array('success' => 200, 'message' => 'Success', 'notification_count'=>0 ));
    }

  } 

  public function notificationList(Request $request){

    $userId = $request['user_id'] ;

     if($userId == ''){
        return json_encode(array('success' => 500, 'message' => 'user_id is Required'));
     }

    // Check User id

    $userr = $this->user_driver->where('id',$userId)->first();
     if(!$userr){
      return json_encode(array('success' => 500, 'message' => 'User Not Exist'));
     }
     
     $notificationList = $this->notifications->where('to_user_id',$userId)->get();

      if(sizeof($notificationList)>0) {

          $this->notifications->where('to_user_id',$userId)->update(['readStatus' => '1']);

          return json_encode(array('success' => 200, 'message' => 'Success', 'notification_list'=>$notificationList)); 
     
        } else {
          
           return json_encode(array('success' => 500, 'message' => 'Fail', 'notification_list'=>'No List Found')); 

      }

  }

  public function place_bid(Request $request)
  {
    
    $driverId = $request['driver_id'] ;
    $jobId = $request['job_id'] ;
    $your_bid = $request['your_bid'] ;
    $delivery_date = $request['delivery_date'] ;
    $delivery_time = $request['delivery_time'] ;

    if($driverId != '') {
    
      // try {
     
         $checkBid = $this->bid->checkBid($jobId);
 
        if ($checkBid)
           { 

             $storeBid = $this->bid->storeBid($request);
 
             if($storeBid)
              { 
               return json_encode(array('success' => 200, 'message' => 'Bid placed Successfully ' )) ;
              }
              else
              {
                return json_encode(array('success' => 500, 'message' => 'Bid not saved' )) ;
              }
            }
           else
           {
            return json_encode(array('success' => 500, 'message' => "Can't place bid" )) ;
           }

      //   } catch (\Exception $e) {

      //     return json_encode(array('success' => 500, 'message' => 'All Parameter Required')) ;

      // } 

      } else {

        return json_encode(array('success' => 500, 'message' => 'Driver ID is Required')) ;
      } 

  }
  ////Accept Bid by Driver Side
  public function myJobs(Request $request)
  {
    $driverId = $request['driver_id'] ; 

    if($driverId != '' ) {

      $post  = $request->all();  
      
      $bidDetails = $this->bid->getBidDetails($driverId) ;
    
        if(sizeof($bidDetails)>0) {

          return json_encode(array('success' => 200, 'message' => 'Success' , 'details' => $bidDetails ));

          } else {
         
            return json_encode(array('success' => 200, 'message' => 'Details not found'));
        
          }

      } else {
          return json_encode(array('success' => 500, 'message' => 'All Parameter Required'));
      }
  }
  

 public function getBidDetails(Request $request)
   {
    
    $driverId = $request['driver_id'] ;
    $jobId = $request['job_id'] ; 

    if($driverId != '' && $jobId != '') {

      $post  = $request->all();  
      
      $bidDetails = $this->bid->getSingleBidDetail($driverId,$jobId) ;
     
        if(!empty($bidDetails)) {

          $imagePath = public_path('/uploads/vehicle/') ;

          $bid_details = [ 
            'driver_bid' => $bidDetails->your_bid , 
            'total_delivery_cost' => $bidDetails->final_price ,
            'service_fee' => '10' ,
            'peerHaul_fee' => '12.8' ,
            'car_name' => $bidDetails->vechicle_make ,
            'car_type' => $bidDetails->vehicle_name , 
            'drivers_total_deliveries' => $bidDetails->successful_deliveries , 
            'drivers_total_reviews' => $bidDetails->review_count , 
            'drivers_average_rating' => $bidDetails->average_rating  ] ;

          return json_encode(array('success' => 200, 'message' => 'Success' , 'details' => $bid_details ));

          } else {
         
             return json_encode(array('success' => 200, 'message' => 'Details not found'));
        
          }

       } else {
          return json_encode(array('success' => 500, 'message' => 'All Parameter Required'));
      }

   }
}

 
