<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Repository\CustomerRepository;
use App\Repository\ListingRepository;
use App\Repository\DriverRepository;
use App\Repository\BidRepository;

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

class CustomerApiController extends Controller
{
    public function __construct(
        CustomerRepository $customer,
        ListingRepository $terms,
        ListingRepository $listing,
        ListingRepository $review,
        ListingRepository $paymenterms,
        CustomerRepository $notificationlist,
        DriverRepository $user_driver,
        BidRepository $bid
    ) {
        $this->customer = $customer;
        $this->terms = $terms;
        $this->review = $review;
        $this->paymenterms = $paymenterms;
        $this->notificationlist = $notificationlist;
        $this->listing = $listing;
        $this->user_driver = $user_driver ;
          $this->bid = $bid ; 
    }

    public function signUp(Request $request)
    {
        $post = $request->all();

        if (!empty($post)) {
            // check Email & Phone
            $email = $post["email"];
            $phone = $post["phone"];

            $checkEmail = $this->customer->checkEmail($email);
            $checkPhone = $this->customer->checkPhone($phone);

            if ($checkEmail) {
                return response()->json([
                    "success" => 500,
                    "message" => "Email ID Already exist",
                ]);
            }

            if ($checkPhone) {
                return response()->json([
                    "success" => 500,
                    "message" => "Phone Number Already exist",
                ]);
            }

            $userCreate = $this->customer->store($request);

            if (!empty($userCreate)) {
                DB::commit();

                $userDetails = $this->customer->checkEmail($email);

                $getDetails["id"] = $userDetails->id;
                $getDetails["name"] = $userDetails->name;
                $getDetails["email"] = $userDetails->email;
                $getDetails["phone"] = $userDetails->phone;
                $getDetails["profile_img"] =
                    URL::to("/") . "/public/uploads/profile_image/default.png";
                $getDetails["fcmToken"] = $userDetails->fcmToken;
                $getDetails["my_referral_code"] =
                    $userDetails->my_referral_code;

                return response()->json([
                    "success" => 200,
                    "message" => "Customer Profile Created Successfully.",
                    "user_details" => $getDetails,
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "Profile Not Created",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "All Parameters Required",
            ]);
        }
    }

    public function customer_login(Request $request)
    {
        try {
            $post = $request->all();

            // check Email
            $checkEmail = $this->customer->checkEmail($post["email"]);

            if ($checkEmail) {
                $userCheck = $this->customer->customerLogin($request);
                $fcmToken = $post["fcmToken"];

                if (!empty($userCheck)) {
                    $update = $this->customer->updateFCM(
                        $userCheck["id"],
                        $fcmToken
                    );
                    $userDetails = $this->customer->checkEmail($post["email"]);

                    $getDetails["id"] = $userDetails->id;
                    $getDetails["username"] = $userDetails->name;
                    $getDetails["email"] = $userDetails->email;
                    $getDetails["phone"] = $userDetails->phone;
                    if ($userDetails->profile_img != " ") {
                        $getDetails["profile_img"] =
                            URL::to("/") .
                            "/public/uploads/profile_image/" .
                            $userDetails->profile_img;
                    } else {
                        $getDetails["profile_img"] =
                            URL::to("/") .
                            "/public/uploads/profile_image/default.png";
                    }
                    $getDetails["fcmToken"] = $userDetails->fcmToken;

                    return response()->json([
                        "success" => 200,
                        "message" => "Login successfully.",
                        "user_details" => $getDetails,
                    ]);
                } else {
                    DB::rollback();
                    return response()->json([
                        "success" => 500,
                        "message" => "Password does not match",
                    ]);
                }
            } else {
                return response()->json([
                    "success" => 500,
                    "message" => "Email Id not Exist",
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "All Parameter Required",
            ]);
        }
    }

    /**
     * Update profile
     * @param object
     */

    public function updateProfile(Request $request)
    {
        $customerId = $request["customer_id"];

        if ($customerId != "") {
            try {
                $update = $this->customer->updateCustomerProfile($request);

                if ($update) {
                    return json_encode([
                        "success" => 200,
                        "message" => "Successfully Updated",
                    ]);
                }

                return json_encode([
                    "success" => 500,
                    "message" => "Not Updated",
                ]);
            } catch (\Exception $e) {
                return json_encode([
                    "success" => 500,
                    "message" => "All Parameter Required",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "Customer ID is Required",
            ]);
        }
    }

    public function getCustomerProfile(Request $request)
    {
        $usrId = $request["customer_id"];

        if ($usrId != "") {
            $userDetails = $this->customer->getCustomerById($usrId);

            if ($userDetails) {
                $getDetails["id"] = $userDetails->id;
                $getDetails["username"] = $userDetails->name;
                $getDetails["email"] = $userDetails->email;
                $getDetails["phone"] = $userDetails->phone;
                $getDetails["date_of_birth"] = $userDetails->date_of_birth;
                if ($userDetails->profile_img != "") {
                    $getDetails["profile_img"] = $userDetails->profile_img;
                } else {
                    $getDetails["profile_img"] = "";
                }

                return response()->json([
                    "success" => 200,
                    "message" => "Success",
                    "user_details" => $getDetails,
                ]);
            } else {
                return json_encode([
                    "success" => 500,
                    "message" => "Data Not Found",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "All Parameter Required",
            ]);
        }
    }

    public function forgetPassword()
    {
        Mail::send(["text" => "mail"], $data, function ($message) {
            $message
                ->to("nancy.ctinfotech@gmail.com", "Tutorials Point")
                ->subject("Laravel Basic Testing Mail");
            $message->from("shikha.ctinfotech@gmail.com", "Virat Gandhi");
        });
    }

    public function reset_password(Request $request)
    {
        $customerId = $request["customer_id"];
        $password = $request["password"];
        if ($customerId != "" && $password != "") {
            $post = $request->all();
            $userr = $this->customer->getCustomerById($customerId);

            if ($userr) {
                if ($password != "") {
                    $update = $this->customer->updatePasssword(
                        $customerId,
                        $password
                    );
                }

                if (!$update) {
                    return json_encode([
                        "success" => 500,
                        "message" => "Not Updated",
                    ]);
                }
                return json_encode([
                    "success" => 200,
                    "message" => "Password Changed Successfully",
                ]);
            } else {
                return json_encode([
                    "success" => 500,
                    "message" => "User not Found",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "All Parameter Required : customer_id, password",
            ]);
        }
    }

    public function updateProfileImage(Request $request)
    {
        if (!empty($_POST)) {
            try {
                $userr = $this->customer->getCustomerById(
                    $request["customer_id"]
                );

                if ($request["profile_image"]) {
                    if ($request->hasFile("profile_image")) {
                        $image = $request->file("profile_image");
                        $img =
                            time() . "." . $image->getClientOriginalExtension();
                        $destinationPath = public_path(
                            "/uploads/profile_image"
                        );
                        $image->move($destinationPath, $img);
                    }
                    $userr->profile_img = $img;
                }

                $userr->save();

                if (!$userr) {
                    return json_encode([
                        "success" => 500,
                        "message" => "Not Updated",
                    ]);
                }

                return json_encode([
                    "success" => 200,
                    "message" => "Successfully Updated",
                    "image" => $img,
                ]);
            } catch (\Exception $e) {
                return json_encode([
                    "success" => 500,
                    "message" => "All Parameter Required",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "All Parameter Required",
            ]);
        }
    }

    ///////multiple upload image

    public function store(Request $request)
    {
   
   if (!empty($_POST)) {
            try {
                $userr = $this->customer->getCustomerById(
                    $request["customer_id"]
                );
                 /////ADD multiple
                             if ($request->hasFile('profile_image')) 
                             {
            $files = $request->file('profile_image');
            $newImage ='';
            foreach($files as $file){
                //$filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $img =   time() .".".$extension;
                $destinationPath = public_path(
                            "/uploads/img/"
                        );
                $file->move($destinationPath, $img);
                $newImage = $newImage.$img;
            }
               $userr->profile_img = $newImage;
            } 
 
            /*
                if ($request["profile_image"]) {
                    if ($request->hasFile("profile_image")) {
                        $image = $request->file("profile_image");
                        $img =
                            time() . "." . $image->getClientOriginalExtension();
                        $destinationPath = public_path(
                            "/uploads/img/"
                        );
                        $image->move($destinationPath, $img);
                    }
                    $userr->profile_img = $img;
                }*/
                

               // $userr->save();

                if (!$userr) {
                    return json_encode([
                        "success" => 500,
                        "message" => "Not Updated",
                    ]);
                }

                return json_encode([
                    "success" => 200,
                    "message" => "Successfully Updated",
                    "image" => $newImage,
                ]);
            } catch (\Exception $e) {
                return json_encode([
                    "success" => 500,
                    "message" => "All Parameter Required",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "All Parameter Required",
            ]);
        }
}
    
    ///////EnD
    // Terms and Condition-  Api Created by Naincy 17/12/21
    public function termsCondition()
    {
        $terms = $this->terms->getTermsData();
        if (!$terms) {
            return json_encode([
                "success" => 500,
                "message" => "No Data Found! ",
            ]);
        } else {
            return json_encode([
                "success" => 200,
                "message" => "Successfully",
                "terms_details" => $terms,
            ]);
        }
    }
    // Payment Terms -  Api Created by Naincy 14/04/2022
    public function paymentTerms()
    {
        $pay = $this->paymenterms->getpaymentTermsData();
        if (!$pay) {
            return json_encode([
                "success" => 500,
                "message" => "No Data Found! ",
            ]);
        } else {
            return json_encode([
                "success" => 200,
                "message" => "Successfully",
                "Payment_Terms_Details" => $pay,
            ]);
        }
    }
    // Get Review Api Created by Naincy 21/12/21
    public function reviewDetails(Request $request)
    {
        $rating = $request->rating;
        $review = $this->review->getReviewList($request);
        if (!$review) {
            return json_encode([
                "success" => 500,
                "message" => "No Data Found! ",
            ]);
        } else {
            return json_encode([
                "success" => 200,
                "message" => "Successfully",
                "review_Details" => $review,
            ]);
        }
    }
    ////// Accept Bid by Customer
    public function acceptBidByCustomer(Request $request)
    {
        $jobId = $request['job_id'] ; 
        $bidId = $request['bid_id'] ; 
        $driverId = $request['driver_id'] ; 
      if($request->job_id != '' &&  $request->bid_id != ''  &&  $request->driver_id != '' )  
      {
        // check driver_id

        $driverId = $request->driver_id ;  
        $checkDriver = $this->user_driver->getDriverById($driverId) ; 
         if(!$checkDriver) 
           {
              return response()->json(['success' => 500,  'message' => 'Driver ID not exist']) ; 
           }
        // check job_id

        $jobId = $request->job_id ; 
       $checkjobId = $this->listing->getListingById($jobId) ; 
        if(!$checkjobId) 
        {
          return response()->json(['success' => 500,  'message' => 'Job ID not exist']) ; 
        } 

     // check bid_id

       // $bidId = $request->bid_id ; 
       // $checkbidId = $this->bid->checkBid($bidId) ; 
       //  if(!$checkbidId) 
       //  {
       //    return response()->json(['success' => 500,  'message' => 'Bid ID not exist']) ; 
       //  } 

        if($driverId != '' && $jobId != '' && $bidId != '')
        {
           $data = $this->listing->updateAcceptBidStatus($request);
         }
        else
          {
           return json_encode(array('success' => 500, 'message' => 'job_id : bid_id : driver_id is required' )); 
         }    
  
    if(!empty($data))
    {   
      return json_encode(array('success' => 200, 'message' => 'Successfully Updated'));
    }
     else
     { 
       return json_encode(array('success' => 500, 'message' => 'No Data Found!' )); 
     }
 }
      else
      {
         DB::rollback();
            return response()->json(['success' => 500, 'message' => 'All Parameters Required : job_id , driver_id , bid_id']);
      }

  
    }
    //////Insert Review Detail 
    public function storeReview(Request $request)
    {
        $post = $request->all();
        if (!empty($post)) {
            $data = $this->review->storeReviewdata($request);
            if (!empty($data)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Review information saved successfully.",
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "Review information not saved",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "All Parameters Required",
            ]);
        }
    }

    public function notificationList(Request $request)
    {
        $customerId = $request["customer_id"];
        $driverId = $request["driver_id"];
        $list = $this->notificationlist->getNotificationList();
        if (!$list) {
            return json_encode([
                "success" => 500,
                "message" => "No Data Found! ",
            ]);
        } else {
            return json_encode([
                "success" => 200,
                "message" => "Successfully",
                "Notificatoion_List " => $list,
            ]);
        }
    }

    public function myListing(Request $request)
    {
        $customerId = $request["customer_id"];
        $search_jobs = $request["search_jobs"];
        $list_type = $request["list_type"]; // 0 = Listing , 1 = Bids
        if ($customerId != "") {
            $list = $this->listing->getmyListingCustomerId(
                $customerId,
                $list_type,
                $search_jobs
            );

            return response()->json([
                "success" => 200,
                "message" => "Success",
                "job_list" => $list,
            ]);
        } else {
            return json_encode([
                "success" => 500,
                "message" => "Customer ID is Required",
            ]);
        }
    }

    public function myDeliveries(Request $request)
    {
        $customerId = $request["customer_id"];
        $search_jobs = $request["search_jobs"];
        $list_type = $request["list_type"]; // 0 = Active , 4 = Completed , 5 = Cancelled
        if ($customerId != "") {
            $list = $this->listing->getmyDeliveriesCustomerId(
                $customerId,
                $list_type,
                $search_jobs
            );
            return response()->json([
                "success" => 200,
                "message" => "Success",
                "job_list" => $list,
            ]);
        } else {
            return json_encode([
                "success" => 500,
                "message" => "Customer ID is Required",
            ]);
        }
    }

    public function uploadVehicleInfo(Request $request)
    {
        $post = $request->all();

        if (!empty($post)) {
            $check_Vinfo = $this->vehicle->getVehicleById(
                $request["customer_id"]
            );

            if (!$check_Vinfo) {
                $userV_info = $this->vehicle->storeVehicleInfo($request);
            } else {
                $userV_info = $this->vehicle->updateVehicleInfo($request);
            }

            if (!empty($userV_info)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Vehicle information saved successfully.",
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "Vehicle information not saved",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "All Parameters Required",
            ]);
        }
    }
}
