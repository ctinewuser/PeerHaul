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

    public function __construct(DriverRepository $user_driver, VehicleRepository $vehicle, BidRepository $bid, ListingRepository $listing)
    {
        $this->user_driver = $user_driver;
        $this->vehicle = $vehicle;
        $this->bid = $bid;
        $this->listing = $listing;
    }
   
     public function getUrlContent($url){

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);

          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
          curl_setopt($ch, CURLOPT_TIMEOUT, 5);
          $data = curl_exec($ch);
          curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);
          // return ($httpcode>=200 && $httpcode<300) ? $data : false;
          return $data ;
      }
   

    public function send_otp(Request $request)
    {
         $phone = $request["phone"];
         $checkPhone = $this
                ->user_driver
                ->checkPhone($phone);

      if($checkPhone)
        {   
            return response()->json(["success" => 500, "message" => "Phone number already registered", ]); 
            // exit ;
         } else {

            if (!empty($phone))
            {
                $otp_number = mt_rand(1111, 9999);
                return response()->json(["success" => 200, "message" => "Otp send successfully", "otp" => $otp_number]);
            }
            else
            {
                DB::rollback();
                return response()->json(["success" => 500, "message" => "Invalid Phone Number", ]);
            }
        }
    }
    public function signUp(Request $request)
    {

        $post = $request->all();

        if (!empty($post))
        {

            // check Email & Phone
            $email = $post['email'];
            $phone = $post['phone'];

            $checkEmail = $this
                ->user_driver
                ->checkEmail($email);
            $checkPhone = $this
                ->user_driver
                ->checkPhone($phone);

            if ($checkEmail)
            {
                return response()->json(['success' => 500, 'message' => 'Email ID Already exist']);
            }

            if ($checkPhone)
            {
                return response()->json(['success' => 500, 'message' => 'Phone Number Already exist']);
            }

            $userCreate = $this
                ->user_driver
                ->store($request);

            if (!empty($userCreate))
            {

                DB::commit();

                $userDetails = $this
                    ->user_driver
                    ->checkEmail($email);

                $getDetails['id'] = $userDetails->id;
                $getDetails['name'] = $userDetails->name;
                $getDetails['email'] = $userDetails->email;
                $getDetails['phone'] = $userDetails->phone;
                $getDetails['profile_img'] = URL::to('/') . "/public/uploads/profile_image/default.png";
                $getDetails['fcmToken'] = $userDetails->fcmToken;
                $getDetails['my_referral_code'] = $userDetails->my_referral_code;

                return response()
                    ->json(['success' => 200, 'message' => 'Profile Created Successfully.', 'user_details' => $getDetails]);

            }
            else
            {

                DB::rollback();
                return response()->json(['success' => 500, 'message' => 'Profile Not Created']);
            }
        }
        else
        {

            DB::rollback();
            return response()
                ->json(['success' => 500, 'message' => 'All Parameters Required']);
        }
    }

    public function driver_login(Request $request)
    {

        try
        {

            $post = $request->all();

            // check Email
            $checkEmail = $this
                ->user_driver
                ->checkEmail($post['email']);
            if ($checkEmail)
            {

                $userCheck = $this
                    ->user_driver
                    ->driverLogin($request);
                $fcmToken = $post['fcmToken'];

                if (!empty($userCheck))
                {
                    $update = $this
                        ->user_driver
                        ->updateFCM($userCheck['id'], $fcmToken);
                    $userDetails = $this
                        ->user_driver
                        ->checkEmail($post['email']);

                    $getDetails['id'] = $userDetails->id;
                    $getDetails['username'] = $userDetails->name;
                    $getDetails['email'] = $userDetails->email;
                    $getDetails['phone'] = $userDetails->phone;
                    if ($userDetails->profile_img != ' ')
                    {
                        $getDetails['profile_img'] = URL::to('/') . "/public/uploads/profile_image/" . $userDetails->profile_img;
                    }
                    else
                    {
                        $getDetails['profile_img'] = URL::to('/') . "/public/uploads/profile_image/default.png";
                    }
                    //$getDetails['profile_img'] = $userDetails->profile_img ;
                    $getDetails['fcmToken'] = $userDetails->fcmToken;

                    return response()
                        ->json(['success' => 200, 'message' => 'Login successfully.', 'user_details' => $getDetails]);

                }
                else
                {

                    DB::rollback();
                    return response()->json(['success' => 500, 'message' => 'Password does not match']);
                }
            }
            else
            {
                return response()
                    ->json(['success' => 500, 'message' => 'Email Id not Exist']);
            }

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'All Parameter Required']);
        }
    }

    /**
     * Update profile
     * @param object
     */
    ////////////
  public function forgotPassword(Request $request)
    {

        $driverId = $request['driver_id'];
     
         $checkDriver = $this->user_driver->getDriverById($driverId);

         $name = '';
         $url = '';
        if($checkDriver->email)
        { 

            $data = array(
            'name' => $checkDriver->name, "url"=>$url
        );
            Mail::send(['html' => "mail"], $data, function ($message) use ($checkDriver)
            {

                $message->to($checkDriver->email,$checkDriver->name)
                    ->subject("Reset Password");
                
            }); 
               return json_encode(["success" => 200, "message" => "Link sent to your email", ]);
         }
        else
        {
            return json_encode(["success" => 500, "message" => "User not Found", ]);

        }
       
    }

    public function my_account(Request $request)
    {

        $driverId = $request['driver_id'];
        if ($driverId != '')
        {
            $userDetails = $this
                ->user_driver
                ->getDriverById($driverId);
            if (!empty($userDetails))
            {
                $getDetails['name'] = $userDetails->name;
                $getDetails['profile_img'] = $userDetails->profile_img;
                $getDetails['account'] = "123456";
                $getDetails['earning_this_year'] = "$20,000.00";
                $getDetails['current_month'] = "$5000";

            }
            return response()->json(['success' => 200, 'message' => 'Success', 'user_details' => $getDetails]);
        }
        else
        {

            return json_encode(array(
                'success' => 500,
                'message' => 'Driver ID is Required'
            ));
        }

    }
    public function acceptBidDetails(Request $request)
    {
        $driverId = $request['driver_id'];
        $detail = $this
            ->bid
            ->getAcceptBidDetail($driverId);
        if (!empty($detail))
        {
            return response()->json(['success' => 200, 'message' => 'Success', 'list' => $detail]);
        }
        else
        {
            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
        }
    }

    public function my_deliveries(Request $request)
    {
        $driverId = $request['driver_id'];
        $filter_type = $request['filter_type']; ////1 jobs , 2 bids , 3 completed
        $search_jobs = $request['status'];
        if ($driverId != '')
        {
            $delivery = $this
                ->listing
                ->getAllJobsByDriverId($driverId);

            if (!empty($delivery))
            {
                $getDetails['name'] = $delivery->name;
                $getDetails['pick_up_location'] = $delivery->pick_up_location;
                $getDetails['drop_off_location'] = $delivery->drop_off_location;
                $getDetails['descriptive_title'] = $delivery->descriptive_title;
                $getDetails['private_information'] = $delivery->private_information;
            }

            return response()
                ->json(['success' => 200, 'message' => 'Success', 'job_details' => $getDetails]);
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'Driver ID is Required'
            ));
        }
    }

    public function updateProfile(Request $request)
    {

        $driverId = $request['driver_id'];

        if ($driverId != '')
        {

            try
            {

                $update = $this
                    ->user_driver
                    ->updateDriverProfile($request);

                if ($update)
                {

                    return json_encode(array(
                        'success' => 200,
                        'message' => 'Successfully Updated'
                    ));

                }

                return json_encode(array(
                    'success' => 500,
                    'message' => 'Not Updated'
                ));

            }
            catch(\Exception $e)
            {

                return json_encode(array(
                    'success' => 500,
                    'message' => 'All Parameter Required'
                ));

            }

        }
        else
        {

            return json_encode(array(
                'success' => 500,
                'message' => 'Driver ID is Required'
            ));
        }
    }

    public function getDriverProfile(Request $request)
    {

        $usrId = $request['driver_id'];

        if ($usrId != '')
        {
            $userDetails = $this
                ->user_driver
                ->getDriverById($usrId);

            if ($userDetails)
            {

                $getDetails['id'] = $userDetails->id;
                $getDetails['username'] = $userDetails->name;
                $getDetails['email'] = $userDetails->email;
                $getDetails['phone'] = $userDetails->phone;

                if ($userDetails->profile_img != '')
                {
                    $getDetails['profile_img'] = 'http://localhost/peer_haul/public/uploads/profile_image/' . $userDetails->profile_img;
                }
                else
                {
                    $getDetails['profile_img'] = '';
                }

                return response()->json(['success' => 200, 'message' => 'Success', 'user_details' => $getDetails]);
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'Data Not Found'
                ));
            }

        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }
    }
   ///////////////////////////////////
    public function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) 
    {
    $theta = $lon1 - $lon2;
    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return compact('miles','feet','yards','kilometers','meters'); 
   /*
    $point1 = array('lat' => 7.452452554, 'long' => 3.4554544545);
    $point2 = array('lat' => 22.636383, 'long' => 75.810692);

    $distance = getDistanceBetweenPoints($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
    foreach ($distance as $unit => $value) {
          echo "test".$unit.': '.number_format($value,2).'<br />';
     }*/
     }


    public function getExpressJobList(Request $request)
    {
         $jobId = $request['job_id'];
         $driverId = $request['driverId'];
          if ($jobId != '' && $driverId != '')
        {
            $detail = $this->listing->getJobDetailById($jobId); 
            $lat1 =   $detail->pick_up_latitude;
            $lon1 =  $detail->pick_up_longitute;
            $lat2 =  $detail->drop_off_latitude;
            $lon2 =   $detail->drop_off_longitute;

                
            // get distance and time

             $origin = $lat1.",".$lon1 ;
             $destination = $lat2.",".$lon2 ;
             $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin."&destination=".$destination."&key=AIzaSyAQ2gCU2hX9OIOeuZJ6lnpp1Xok_ld7DaI" ;
             $content = SELF::getUrlContent($url) ;
             $json = (Array)json_decode($content, true) ; 
             //$distance = $json["routes"][0]["legs"][0]["distance"]["text"] ;
             $duration = $json["routes"][0]["legs"][0]["duration"]["text"] ;
           
            // $getMin = explode(' ',$duration) ;  
            // print_r($getMin);
            // die;
          /* $minutes_to_add = $getMin[0] + $detail->pick_up_latitude ;
            $time = new DateTime($checkRequest->date_time);
            $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
            $get_end_time = $time->format('Y-m-d H:i:s');
            */
            ////////////////////////////////////////////////////
            $distance =  $this->getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2);
            $distance1= number_format($distance['kilometers'],2);

             //////////Dropoff location to driver location (dropoff_distance) 
            $getdriverdetail = $this->user_driver->getDriverById($request['driverId']);

            $driverLat1 =  $getdriverdetail->latitude;
            $driverLong1= $getdriverdetail->longitude;
            $driverDistance =  $this->getDistanceBetweenPoints($lat2, $lon2, $driverLat1, $driverLong1);
            $driverDistance1= number_format($driverDistance['kilometers'],2);

             //////////Driver location to Pickup Location (distance_from_me)
          
            $distanceFromMe =  $this->getDistanceBetweenPoints($driverLat1, $driverLong1 ,$lat1 , $lon1);
            $distanceFromMe1= number_format($distanceFromMe['kilometers'],2);
            
            if($detail->express_listing)
            {
              $getList = $this
                ->listing
                ->getExpressListByJobId($jobId);

                $getDetails1 =array();
                if ($getList[0]->photos == "[]" )
                { 
                   $getoneimg = "";
                }
                else
                {
                    $img = json_decode($getList[0]->photos);
                    foreach ($img as $getimg)
                    {
                        $getDetails1[] = URL::to('/') . "/public/uploads/img/" .$getimg;
                       
                         $getList[0]->photos = $getDetails1;
                    } 
                }
                  
                  $delivery_date = $getList[0]->delivery_date;
                 $taken_time = $getList[0]->taken_time;
                 $delivery_deadline = date('Y-m-d', strtotime($delivery_date. ' + '.$taken_time)); 

                 $getList[0]->dropoff_distance = round($driverDistance1);
                 $getList[0]->delivery_deadline = $delivery_deadline;
                 $getList[0]->driving_time = $duration;
                 $getList[0]->distance_from_me= round($distanceFromMe1);

                
                  if(!empty($distance1))
                  {
                     $getList[0]->distance= round($distance1);

                  }else{
                       $getList[0]->distance = "";
                  }
          }  
           else
            {
              return json_encode(array(
                    'success' => 500,
                    'message' => 'Data not Found'));
                }
         
         return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully',
                    'list' =>$getList[0] ));
        }
        
        else
        {
          return json_encode(array(
                    'success' => 500,
                    'message' => 'Data not Found'));
        }

    }
     ///////////////////////////////////
    public function getSuggestedCostJobList(Request $request)
    {
         $jobId = $request['job_id'];
         $driverId = $request['driver_id'];
        
          if ($jobId != '' && $driverId != '')
        {
              $detail = $this
                ->listing
                ->getJobDetailById($jobId); 
            if($detail->express_listing)
            {
              $getList = $this
                ->listing
                ->getSuggestedBidList($driverId);
                }
           else
            {
              return json_encode(array(
                    'success' => 500,
                    'message' => 'Data not Found'));
                }
         return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully',
                    'list' => $getList));
        }
        
        else
        {
          return json_encode(array(
                    'success' => 500,
                    'message' => 'Data not Found'));
        }
    }
   
    ////////Find Distance in km

     public function distance() {
       $lat1 = 23.2599;
        $lat2 = 22.7196;
        $lon1 = 77.4126;
        $lon2 = 75.8577;
        $unit ="K";
        $theta ="";
        $miles ="";
        $dist ="";
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

// echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
    ///////////////////////////////////
    public function change_password(Request $request)
    {
        $driverId = $request['driver_id'];
        $password = $request['password'];
        if ($driverId != '' && $password != '')
        {

            $post = $request->all();
            $userr = $this
                ->user_driver
                ->getDriverById($driverId);

            if ($userr)
            {

                /* // check old password
                $checkPass = $this->user_driver->where('password',md5($post['old_password']))->where('id',$usrId)->first();
                if(!$checkPass) {
                return response()->json(['success' => 500,'message' =>'Old Password not Match']);
                } */

                if ($password != '')
                {

                    $update = $this
                        ->user_driver
                        ->updatePasssword($driverId, $password);

                }

                if (!$update)
                {
                    return json_encode(array(
                        'success' => 500,
                        'message' => 'Not Updated'
                    ));
                }
                return json_encode(array(
                    'success' => 200,
                    'message' => 'Password Changed Successfully'
                ));

            }
            else
            {

                return json_encode(array(
                    'success' => 500,
                    'message' => 'User not Found'
                ));
            }

        }
        else
        {

            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required : driver_id, password'
            ));
        }
    }

    public function updateDriverProfileImage(Request $request)
    {
        if (!empty($_POST))
        {

            try
            {
                $userr = $this
                    ->user_driver
                    ->getDriverById($request['driver_id']);
                 // print_r($userr);
                 // die;
                if ($request['profile_image'])
                {

                    if ($request->hasFile('profile_image'))
                    {
                        $image = $request->file('profile_image');
                        $img = time() . '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/profile_image/');
                        $image->move($destinationPath, $img);
                    }
                    $userr->profile_img = $img;

                       ////to get url 
                         $a = trim($img, '[');
                          $b = trim($a, ']');
                        $getDetails = URL::to('/') . "/public/uploads/profile_image/" . trim($b, '"');
                    
                }
       
                $userr->save();
                  
                if (!$userr)
                {

                    return json_encode(array(
                        'success' => 500,
                        'message' => 'Not Updated'
                    ));
                }

                return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully Updated',
                    'image' => $getDetails
                ));

            }
            catch(\Exception $e)
            {

                return json_encode(array(
                    'success' => 500,
                    'message' => 'All Parameter Required'
                ));

            }
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }
    }

    public function uploadVehicleInfold(Request $request)
    {

        $post = $request->all();

        if (!empty($post))
        {
            $check_Vinfo = $this
                ->vehicle
                ->getVehicleById($request['driver_id']);

            if (!$check_Vinfo)
            {
                $userV_info = $this
                    ->vehicle
                    ->storeVehicleInfo($request);
            }
            else
            {
                $userV_info = $this
                    ->vehicle
                    ->updateVehicleInfo($request);
            }

            if (!empty($userV_info))
            {

                return response()->json(['success' => 200, 'message' => 'Vehicle information saved successfully.']);

            }
            else
            {

                DB::rollback();
                return response()
                    ->json(['success' => 500, 'message' => 'Vehicle information not saved']);
            }

        }
        else
        {

            DB::rollback();
            return response()
                ->json(['success' => 500, 'message' => 'All Parameters Required']);
        }

    }

//////////////////////////////////////////////
 public function uploadVehicleInfo(Request $request)
    {

        $post = $request->all();

        if (!empty($post))
        {
            $check_Vinfo = $this
                ->vehicle
                ->getVehicleById($request['driver_id']);

            if (!$check_Vinfo)
            {
                $userV_info = $this
                    ->vehicle
                    ->storeVehicleInfo($request);
            }
            else
            {
                $userV_info = $this
                    ->vehicle
                    ->updateVehicleInfo($request);
            }

            if (!empty($userV_info))
            {

                return response()->json(['success' => 200, 'message' => 'Vehicle information saved successfully.']);

            }
            else
            {

                DB::rollback();
                return response()
                    ->json(['success' => 500, 'message' => 'Vehicle information not saved']);
            }

        }
        else
        {

            DB::rollback();
            return response()
                ->json(['success' => 500, 'message' => 'All Parameters Required']);
        }

    }






    public function vehicle_type(Request $request)
    {
        $vehicle_type = $this
            ->vehicle
            ->vehicle_type();
        return response()
            ->json(['success' => 200, 'message' => 'Vehicle type list .', 'vehicle-type-list' => $vehicle_type]);
    }

    public function notificationCount(Request $request)
    {

        $userId = $request['user_id'];
        $notificationList = $this
            ->notifications
            ->where('to_user_id', $userId)->where('readStatus', '0')
            ->get();

        if (sizeof($notificationList) > 0)
        {
            return json_encode(array(
                'success' => 200,
                'message' => 'Success',
                'notification_count' => count($notificationList)
            ));
        }
        else
        {
            return json_encode(array(
                'success' => 200,
                'message' => 'Success',
                'notification_count' => 0
            ));
        }

    }

    public function notificationList(Request $request)
    {

        $userId = $request['user_id'];

        if ($userId == '')
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'user_id is Required'
            ));
        }

        // Check User id
        $userr = $this
            ->user_driver
            ->where('id', $userId)->first();
        if (!$userr)
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'User Not Exist'
            ));
        }

        $notificationList = $this
            ->notifications
            ->where('to_user_id', $userId)->get();

        if (sizeof($notificationList) > 0)
        {

            $this
                ->notifications
                ->where('to_user_id', $userId)->update(['readStatus' => '1']);

            return json_encode(array(
                'success' => 200,
                'message' => 'Success',
                'notification_list' => $notificationList
            ));

        }
        else
        {

            return json_encode(array(
                'success' => 500,
                'message' => 'Fail',
                'notification_list' => 'No List Found'
            ));

        }

    }

    public function place_bid(Request $request)
    {

        $driverId = $request['driver_id'];
        $jobId = $request['job_id'];
        $your_bid = $request['your_bid'];
        $delivery_date = $request['delivery_date'];
        $delivery_time = $request['delivery_time'];

        if ($driverId != '')
        {

            try {
            $checkBid = $this
                ->bid
                ->checkBid($jobId);

            if ($checkBid)
            {

                $storeBid = $this
                    ->bid
                    ->storeBid($request);

                if ($storeBid)
                {
                    return json_encode(array(
                        'success' => 200,
                        'message' => 'Bid placed Successfully '
                    ));
                }
                else
                {
                    return json_encode(array(
                        'success' => 500,
                        'message' => 'Bid not saved'
                    ));
                }
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => "Can't place bid"
                ));
            }

              } catch (\Exception $e) {
                return json_encode(array('success' => 500, 'message' => 'All Parameter Required')) ;
            }
            
        }
        else
        {

            return json_encode(array(
                'success' => 500,
                'message' => 'Driver ID is Required'
            ));
        }

    }

    ///////Bid 
    ////Accept Bid by Driver Side
    public function myJobs(Request $request)
    {
        $driverId = $request['driver_id'];

        if ($driverId != '')
        {

            $post = $request->all();

            $bidDetails = $this
                ->bid
                ->getBidDetails($driverId);

            if (sizeof($bidDetails) > 0)
            {

                return json_encode(array(
                    'success' => 200,
                    'message' => 'Success',
                    'details' => $bidDetails
                ));

            }
            else
            {

                return json_encode(array(
                    'success' => 200,
                    'message' => 'Details not found'
                ));

            }

        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }
    }
    ///////////////////////
    public function allListing(Request $request)
    {

         $query = DB::table('tbl_job_listing')
            ->select('tbl_job_listing.*','tbl_item_information.*','tbl_delivery_information.*')
             ->join('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
              ->join('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
           ->where('tbl_job_listing.job_status', '=', 4)
          // ->where('tbl_job_listing.bid_status', '=', 1)
            ->where('tbl_job_listing.job_status', '!=', 6)
            ->get();
       //////////////////////////
               //////get url for images
            foreach ($query as $key => $value)
            {
                $getDetails1 = array();
                if ($value->upload_photos == "[]" )
                { 
                   $getoneimg = "";
                }
                else
                {
                        $img = explode(',', $value->upload_photos);
                    foreach ($img as $key => $getimg)
                    {
                        $a = trim($getimg, '[');
                        $b = trim($a, ']');
                        $getDetails1[0] = URL::to('/') . "/public/uploads/img/" . trim($b, '"');
                       $getoneimg =  implode(" ",$getDetails1);
                    } 

                }
                $getDetails[] = ['listing_id' => $value->listing_id,'customer_id' => $value->customer_id,'parcel_size'=> $value->parcel_size,'job_post_time' => date('h:i:s A',$value->job_post_time),'descriptive_title' => $value->descriptive_title, 'pick_up_location' => $value->pick_up_location , 'bid_status' => $value->bid_status,'bid_count' => $value->bid_count, 'drop_off_location' => $value->drop_off_location,'add_bonus'=> $value->add_bonus, 'estimate_price' => $value->estimate_price, 'express_listing' => $value->express_listing, 'job_status' => $value->job_status,'upload_photos' => $getoneimg];
            }
         ///////////////////////////   
     if($getDetails)
          {
            return response()->json(["success" => 200, "message" => "Success", "job_list" => $getDetails, ]);
        }
              else
            {

                return json_encode(array(
                    'success' => 500,
                    'message' => 'Details not found'
                ));

            }
        }
        
    

























    ////Completed bid detail//
    public function getCompletedBidList(Request $request)
    {
      $driverId = $request['driver_id'];
    
      $checkDriver =  $this->listing->getJobDetailByDriverId($driverId);
     
      if ($checkDriver->driver_id)
      {
         $bidDetails = $this
                ->bid
                ->getCompletedBidList($driverId);
        return response()->json(["success" => 200, "message" => "Success", "bid_list" => $bidDetails, ]);
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Driver ID is Required", ]);
        }



     }
    //////////End

    ///////Upload PickUp Product Image
   public function uploadProductImage(Request $request)
   {
     if (!empty($_POST))
        {
            try
            {
                $userr = $this
                    ->user_driver
                    ->getDriverById($request["driver_id"]);

                ///Note:- Function does not save same image
                $files = [];
                if ($request->hasfile('filenames'))
                {
                    foreach ($request->file('filenames') as $file)
                    {
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationPath = public_path("/uploads/img/");
                        $file->move($destinationPath, $name);
                        $files[] = $name;
                    }
                }

                $file = new File();
                $file->filenames = $files;

                if (!$userr)
                {
                    return json_encode(["success" => 500, "message" => "Not Updated", ]);
                }

                return json_encode(["success" => 200, "message" => "Successfully Product Image Uploaded", "image" => $files, ]);
            }
            catch(\Exception $e)
            {
                return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
        }
   }
    public function uploadDropOffImage(Request $request)
   {
     if (!empty($_POST))
        {
            try
            {
                $userr = $this
                    ->user_driver
                    ->getDriverById($request["driver_id"]);

                ///Note:- Function does not save same image
                $files = [];
                if ($request->hasfile('filenames'))
                {
                    foreach ($request->file('filenames') as $file)
                    {
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationPath = public_path("/uploads/dropoff/");
                        $file->move($destinationPath, $name);
                        $files[] = $name;
                    }
                }

                $file = new File();
                $file->filenames = $files;

                if (!$userr)
                {
                    return json_encode(["success" => 500, "message" => "Not Updated", ]);
                }

                return json_encode(["success" => 200, "message" => "Successfully DropOff Image Uploaded", "image" => $files, ]);
            }
            catch(\Exception $e)
            {
            return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
        }
   }
     
    /////////// insert Account Detail
   public function addAccountDetail(Request $request)
   {
      //Note: Need to manage if account detail already exist for user 
        $driverId = $request['driver_id'];
        $holder_name = $request['holder_name'];
        $branch_name = $request['branch_name'];
        $account_name = $request['account_name'];
        $ifsc_code = $request['ifsc_code'];

        if ($driverId != '')
        {
          $checkDriver = $this
                    ->user_driver
                    ->getDriverById($request["driver_id"]);

       if($checkDriver)
            {
              $data = $this
                ->listing
                ->addAccount($request);
                }
           else
            {
              return json_encode(array(
                    'success' => 500,
                    'message' => 'Data not Found'));
                }
        return response()->json(["success" => 200, "message" => "Account detail saved successfully.", ]);
     }
     else{
        return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required' 
                 ));
     }

   }

   /////////////////Transaction History
    public function getTransactionDetail(Request $request)
    {
         $driverId = $request['driver_id'];
        
          if ($driverId != '')
        {
           $data = $this->listing->getTransactionDetail();
           if(!empty($data))
           {
              return json_encode(array(
                    'success' => 200,
                    'message' => 'Success',
                    'details' => $data
                ));
           }
           else
           {
              return json_encode(array(
                    'success' => 200,
                    'message' => 'Details not found'
                ));
           }

        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            )); 
        }
    }

     //////////End//  
    ///////////////Start Job
    /*public function startJob(Request $request)
    {
         $driverId = $request['driver_id'];
         $jobId = $request['job_id'];
         if ($driverId != '' && $jobId != '')
        {
          $driverId = $request->driver_id;
           $checkDriver = $this
                ->user_driver
                ->getDriverById($driverId);
                if (!$checkDriver)
            {
                return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
            }
            $data = $this->listing->getStartJobDetail();
           return json_encode(array(
                    'success' => 200,
                    'message' => 'Success',
                    'details' => $data
                )); 

         }
         else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }

    }*/
    ///////Complete DropOff  3 = delivered status 
    public function getCompleteDropOff(Request $request)
   {
      $driverId = $request['driver_id'];
        $jobId = $request['job_id'];
         if ($request->driver_id != '' && $request->job_id != '')
        {
             $driverId = $request->driver_id;
           $checkDriver = $this
                ->user_driver
                ->getDriverById($driverId);
                if (!$checkDriver)
            {
                return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
            }
          // check job_id
            $jobId = $request->job_id;
            $checkjobId = $this
                ->listing
                ->getListingById($jobId);
            if (!$checkjobId)
            {
                return response()->json(['success' => 500, 'message' => 'Job ID not exist']);
            }

             if ($driverId != '' && $jobId != '')
            {
                $data = $this
                    ->listing
                    ->updateCompleteDropOffStatus($request);
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'job_id : driver_id is required'
                ));
            }
            if (!empty($data))
            {
                return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully Updated'
                ));
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'No Data Found!'
                ));
            }

         }
         else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }
   }
   //////////////////////
   public function getPickupDetail(Request $request)
   {
        $driverId = $request['driver_id'];
        $jobId = $request['job_id'];
      

        if($driverId != '' && $jobId != '')
         {
            $driverId = $request->driver_id;
           $checkDriver = $this
                ->user_driver
                ->getDriverById($driverId);
                if (!$checkDriver)
            {
                return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
            }
            $data = $this
                    ->listing
                    ->getPersonPickupDetail($jobId); 

            return json_encode(["success" => 200, "message" => "Successfully", "info" => $data, ]);

         }
        else
         {
           return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }

   }
   //////////Complete PickUp 2 = inTransit status 
    public function getCompletePickUp(Request $request)
   {
        $driverId = $request['driver_id'];
        $jobId = $request['job_id'];
         if ($request->driver_id != '' && $request->job_id != '')
        {
             $driverId = $request->driver_id;
           $checkDriver = $this
                ->user_driver
                ->getDriverById($driverId);
                if (!$checkDriver)
            {
                return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
            }
          // check job_id
            $jobId = $request->job_id;
            $checkjobId = $this
                ->listing
                ->getListingById($jobId);
            if (!$checkjobId)
            {
                return response()->json(['success' => 500, 'message' => 'Job ID not exist']);
            }

             if ($driverId != '' && $jobId != '')
            {
                $data = $this
                    ->listing
                    ->updateCompletePickUpStatus($request);
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'job_id : driver_id is required'
                ));
            }
            if (!empty($data))
            {
                return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully Updated'
                ));
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'No Data Found!'
                ));
            }

         }
         else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }
   }
   ///////5=cancelled Cancel JOb 5=cancelled status
    public function cancelledJob(Request $request)
    {
       $driverId = $request['driver_id'];
        $jobId = $request['job_id'];
         if ($request->driver_id != '' && $request->job_id != '')
        {
             $driverId = $request->driver_id;
           $checkDriver = $this
                ->user_driver
                ->getDriverById($driverId);
                if (!$checkDriver)
            {
                return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
            }
          // check job_id
            $jobId = $request->job_id;
            $checkjobId = $this
                ->listing
                ->getListingById($jobId);
            if (!$checkjobId)
            {
                return response()->json(['success' => 500, 'message' => 'Job ID not exist']);
            }

             if ($driverId != '' && $jobId != '')
            {
                $data = $this
                    ->listing
                    ->updateCancelJobStatus($request);
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'job_id : driver_id is required'
                ));
            }
            if (!empty($data))
            {
                return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully Updated'
                ));
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'No Data Found!'
                ));
            }

         }
         else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        } 
    }
    ////Add Debit/Credit Card 
     public function addCardDetail(Request $request)
    {
       $driverId = $request['driver_id'];
       $firstName = $request['first_name'];
       $lastName = $request['last_name'];
       $cardNumber = $request['card_number'];
       $securityCode = $request['security_code'];
       $expiryMonth = $request['expiry_month'];
       $expiryYear = $request['expiry_year'];
       
       if($driverId != '' && $cardNumber != '' &&  $securityCode != '' && $expiryMonth != '' && $expiryYear != '')
        {  
         $driverId = $request['driver_id'];
         $checkDriver = $this->user_driver->getDriverById($driverId);
         if (!$checkDriver)
            {
             return response()->json(['success' => 500, 'message' => 'Driver ID not exist']);
            }

          $data = $this->user_driver->insertCardDetail($request);
          return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully card detail inserted'
                ));
          }
       else
        {
           return json_encode(array(
                'success' => 500,
                'message' => 'card_number : security_code :expiry_month: driver_id is required'
            ));
        }
    }
    ////////END
    /////////savedCardList ////
      public function savedCardList(Request $request)
    {
       $driverId = $request['driver_id'];
       if ($request->driver_id != '')
        {
           $data = $this->listing->cardDetails($request); 
           if(!empty($data))
           {
                return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully data inserted'
                ));
           }
           else
           {
               return json_encode(array(
                    'success' => 200,
                    'message' => 'Details not found'
                ));
           }
        }
         else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }

    }

    //////////End//
    public function deleteCardDetail(Request $request)
    {
        $cardId = $request['card_id'];
        $driverId = $request['driver_id'];
        if ($cardId != '' && $driverId != '')
        {
             $data = $this->listing->delcardDetails($request);
              if(!empty($data))
           {
                 return json_encode(array(
                    'success' => 200,
                    'message' => 'Successfully card deleted'
                ));
           }
           else
           {
               return json_encode(array(
                    'success' => 200,
                    'message' => 'Details not found'
                ));
           }
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            )); 
        }

    }
    public function getBidDetails(Request $request)
    {

        $driverId = $request['driver_id'];
        $jobId = $request['job_id'];

        if ($driverId != '' && $jobId != '')
        {

            $post = $request->all();

            $bidDetails = $this
                ->bid
                ->getSingleBidDetail($driverId, $jobId);

            if (!empty($bidDetails))
            {

                $imagePath = public_path('/uploads/vehicle/');

                $bid_details = ['driver_bid' => $bidDetails->your_bid, 'total_delivery_cost' => $bidDetails->final_price, 'service_fee' => '10', 'peerHaul_fee' => '12.8', 'car_name' => $bidDetails->vechicle_make, 'car_type' => $bidDetails->vehicle_name, 'drivers_total_deliveries' => $bidDetails->successful_deliveries, 'drivers_total_reviews' => $bidDetails->review_count, 'drivers_average_rating' => $bidDetails->average_rating];

                return json_encode(array(
                    'success' => 200,
                    'message' => 'Success',
                    'details' => $bid_details
                ));

            }
            else
            {

                return json_encode(array(
                    'success' => 200,
                    'message' => 'Details not found'
                ));

            }

        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'All Parameter Required'
            ));
        }

    }
}

