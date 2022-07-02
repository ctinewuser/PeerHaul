<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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
use DateInterval;

class CustomerApiController extends Controller
{
    public function __construct(CustomerRepository $customer, ListingRepository $terms, ListingRepository $listing, ListingRepository $review, ListingRepository $paymenterms, CustomerRepository $notificationlist, DriverRepository $user_driver, BidRepository $bid)
    {
        $this->customer = $customer;
        $this->terms = $terms;
        $this->review = $review;
        $this->paymenterms = $paymenterms;
        $this->notificationlist = $notificationlist;
        $this->listing = $listing;
        $this->user_driver = $user_driver;
        $this->bid = $bid;
    }

    public function send_otp(Request $request)
    {
         $phone = $request["phone"];
         $checkPhone = $this
                ->customer
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
            $email = $post["email"];
            $phone = $post["phone"];
            $checkEmail = $this
                ->customer
                ->checkEmail($email);
            $checkPhone = $this
                ->customer
                ->checkPhone($phone);

            if ($checkEmail)
            {
                return response()->json(["success" => 500, "message" => "Email ID Already exist", ]);
            }

            if ($checkPhone)
            {
            return response()->json(["success" => 500, "message" => "Phone Number Already Registered ", ]);
            }

            $userCreate = $this
                ->customer
                ->store($request);

            if (!empty($userCreate))
            {
                DB::commit();

                $userDetails = $this
                    ->customer
                    ->checkEmail($email);

                $getDetails["id"] = $userDetails->id;
                $getDetails["name"] = $userDetails->name;
                $getDetails["email"] = $userDetails->email;
                $getDetails["phone"] = $userDetails->phone;
                $getDetails["profile_img"] = URL::to("/") . "/public/uploads/profile_image/default.png";
                $getDetails["fcmToken"] = $userDetails->fcmToken;
                $getDetails["my_referral_code"] = $userDetails->my_referral_code;
                $getDetails["otp"] = $userDetails->otp;

                return response()
                    ->json(["success" => 200, "message" => "Customer Profile Created Successfully.", "user_details" => $getDetails]);
            }
            else
            {
                DB::rollback();
                return response()->json(["success" => 500, "message" => "Profile Not Created", ]);
            }
        }
        else
        {
            DB::rollback();
            return response()
                ->json(["success" => 500, "message" => "All Parameters Required", ]);
        }
    }

    public function customer_login(Request $request)
    {
        try
        {
            $post = $request->all();

            // check Email
            $checkEmail = $this
                ->customer
                ->checkEmail($post["email"]);

            if ($checkEmail)
            {
                $userCheck = $this
                    ->customer
                    ->customerLogin($request);
                $fcmToken = $post["fcmToken"];

                if (!empty($userCheck))
                {
                    $update = $this
                        ->customer
                        ->updateFCM($userCheck["id"], $fcmToken);
                    $userDetails = $this
                        ->customer
                        ->checkEmail($post["email"]);

                    $getDetails["id"] = $userDetails->id;
                    $getDetails["username"] = $userDetails->name;
                    $getDetails["email"] = $userDetails->email;
                    $getDetails["phone"] = $userDetails->phone;
                    $getDetails["otp"] = $userDetails->otp;
                    $getDetails["date_of_birth"] = $userDetails->date_of_birth;
                    $getDetails["house_no"] = $userDetails->house_no;
                    $getDetails["address"] = $userDetails->address;
                    $getDetails["state"] = $userDetails->state;
                    $getDetails["city"] = $userDetails->city;
                    $getDetails["post_code"] = $userDetails->post_code;
                    $getDetails["total_review"] = $userDetails->total_review;
                    $getDetails["is_company"] = $userDetails->is_company;
                    $getDetails["company_name"] = $userDetails->company_name;
             $getDetails["my_referral_code"] = $userDetails->my_referral_code;
                  $getDetails["referred_code"] = $userDetails->referred_code;
                  $getDetails["device_type"] = $userDetails->device_type;
                    if ($userDetails->profile_img != " ")
                    {
                        $getDetails["profile_img"] = URL::to("/") . "/public/uploads/profile_image/" . $userDetails->profile_img;
                    }
                    else
                    {
                        $getDetails["profile_img"] = URL::to("/") . "/public/uploads/profile_image/default.png";
                    }
                    $getDetails["fcmToken"] = $userDetails->fcmToken;
                    
                   ////CHECK IF PERSONAL DETAIL IS FILLED OR NOT

                    if($userDetails->date_of_birth != '')
                    {
                      $is_personal_info = 1;
                    }
                    else
                    {
                     $is_personal_info = 0;
                     }

                    $getDetails["is_personal_info"] = $is_personal_info;
                   
                    return response()
                        ->json(["success" => 200, "message" => "Login successfully.", "user_details" => $getDetails, ]);
                }
                else
                {
                    DB::rollback();
                    return response()->json(["success" => 500, "message" => "Password does not match", ]);
                }
            }
            else
            {
                return response()
                    ->json(["success" => 500, "message" => "Email ID Does Not Exist", ]);
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(["success" => 500, "message" => "All Parameter Required", ]);
        }
    }

    /**
     * Update profile
     * @param object
     */
  public function updateProfile(Request $request)
    {
       $customerId = $request["customer_id"];
         
        if($customerId != "")
         {
           // Check Customer Id
           $checkCustomerId = $this->customer->getCustomerById($customerId) ;

           if(!$checkCustomerId)
           {
            return json_encode(["success" => 500, "message" => "Customer Id does not exist" ]); 
           }

            try
            {
            // //////////////////
         $checkPhone = $this
                ->customer
                ->checkPhoneUpdate($request->phone,$request["customer_id"]);

        if($checkPhone)
        {   
            return response()->json(["success" => 500, "message" => "Phone number already registered", ]);   
        }

        /*if ($request["phone"])
              {
                $req['phone'] = $request->phone;
             }
        else
            {
                $otp_number = mt_rand(1111, 9999);
               response()->json(["success" => 200, "message" => "Otp send successfully", "otp" => $otp_number]);
             }
*/
         $checkEmail = $this->customer->checkEmailUpdate($request->email,$request["customer_id"]);
  
               if ($checkEmail)
                 {
                return json_encode(["success" => 500, "message" => "Email Id already exist" ]);
                 } 
                  else 
                 { 
                  
                    $update = $this
                        ->customer
                        ->updateCustomerProfile($request);
                    
                    if ($update)
                    {
                        return json_encode(["success" => 200, "message" => "Successfully Updated",  'user_details'=>$checkCustomerId]);
                    }

                    return json_encode(["success" => 500, "message" => "Not Updated", ]);
                 }
               
            }
            catch(\Exception $e)
            {
                return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Customer ID is Required", ]);
        }
    }
    public function updateProfile1(Request $request)
    {
        $customerId = $request["customer_id"];

        if ($customerId != "")
        {
            try
            {
                $update = $this
                    ->customer
                    ->updateCustomerProfile($request);
                
                $userdetails = $this
                    ->customer
                    ->getCustomerById($request["customer_id"]);
               // print_r($userdetails);
               // die;

                if ($update)
                {
                    return json_encode(["success" => 200, "message" => "Successfully Updated", 'user_details'=>$userdetails]);
                }

                return json_encode(["success" => 500, "message" => "Not Updated", ]);
            }
            catch(\Exception $e)
            {
                return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Customer ID is Required", ]);
        }
    }

    public function getCustomerProfile(Request $request)
    {
        $usrId = $request["customer_id"];

        if ($usrId != "")
        {
            $userDetails = $this
                ->customer
                ->getCustomerById($usrId);

            if ($userDetails)
            {
                $getDetails["id"] = $userDetails->id;
                $getDetails["username"] = $userDetails->name;
                $getDetails["email"] = $userDetails->email;
                $getDetails["phone"] = $userDetails->phone;
                $getDetails["otp"] = $userDetails->otp;
                $getDetails["date_of_birth"] = $userDetails->date_of_birth;
                 $getDetails["house_no"] = $userDetails->house_no;
                $getDetails["address"] = $userDetails->address;
                $getDetails["state"] = $userDetails->state;
                $getDetails["city"] = $userDetails->city;
                $getDetails["post_code"] = $userDetails->post_code;
                $getDetails["total_review"] = $userDetails->total_review;
                $getDetails["is_company"] = $userDetails->is_company;
                $getDetails["company_name"] = $userDetails->company_name;
             $getDetails["my_referral_code"] = $userDetails->my_referral_code;
                  $getDetails["referred_code"] = $userDetails->referred_code;
                  $getDetails["device_type"] = $userDetails->device_type;
                if ($userDetails->profile_img != "")
                {
                    $getDetails["profile_img"] = URL::to("/") . "/public/uploads/profile_image/" . $userDetails->profile_img;
                }
                else
                {
                    $getDetails["profile_img"] = "";
                }

             return response()->json(["success" => 200, "message" => "Success", "user_details" => $getDetails, ]);
            }
            else
            {
                return json_encode(["success" => 500, "message" => "Data Not Found", ]);
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => "All Parameter Required", ]);
        }
    }

    public function oldforgotPassword()
    {
        $data = array(
            'name' => "Nancy Gupta",
            "url" => "test.com"
        );
        Mail::send(['html' => "mail"], $data, function ($message)
        {

            $message->to("nancy.ctinfotech@gmail.com", "Tutorials Point")
                ->subject("Laravel Basic Testing Mail");

        });
    }
    public function forgotPassword(Request $request)
    {
        $email = $request["email"];

        $checkCustomer = $this
            ->customer
            ->getCustomerByEmail($email);

        if ($checkCustomer)
        {

            $checkId = $checkCustomer->id;
            $encrypt = encrypt($checkId);
            $name = '';
            $url = 'https://ctinfotech.com/peerhaul/#/reset-password/' . $encrypt;

            $data = array(
                'name' => $checkCustomer->name,
                "url" => $url
            );
            Mail::send(['html' => "mail"], $data, function ($message) use ($checkCustomer)
            {

                $message->to($checkCustomer->email, $checkCustomer->name)
                    ->subject("Reset Password");

            });
            return json_encode(["success" => 200, "message" => "Link sent to your email", ]);
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Email address not found", ]);

        }

    }

    public function change_password(Request $request)
    {
        $decrypt = $request["customer_id"];
        $customerId = decrypt($decrypt);

        $password = $request["password"];
        if ($customerId != "" && $password != "")
        {
            $post = $request->all();
            $userr = $this
                ->customer
                ->getCustomerById($customerId);

            if ($userr)
            {
                if ($password != "")
                {
                    $update = $this
                        ->customer
                        ->updatePasssword($customerId, $password);
                }

                if (!$update)
                {
                    return json_encode(["success" => 500, "message" => "Not Updated", ]);
                }
                return json_encode(["success" => 200, "message" => "Password Changed Successfully", ]);
            }
            else
            {
                return json_encode(["success" => 500, "message" => "User not Found", ]);
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => "All Parameter Required : customer_id, password", ]);
        }
    }

    public function updateProfileImage(Request $request)
    {
        if (!empty($_POST))
        {
            try
            {
                $userr = $this
                    ->customer
                    ->getCustomerById($request["customer_id"]);

                if ($request["profile_image"])
                {
                    if ($request->hasFile("profile_image"))
                    {
                        $image = $request->file("profile_image");
                        $img = time() . "." . $image->getClientOriginalExtension();
                        $destinationPath = public_path("/uploads/profile_image");
                        $image->move($destinationPath, $img);
                    }
                    $userr->profile_img = $img;
                    $pro_imgage = URL::to("/") . "/public/uploads/profile_image/".$img;
                }

                $userr->save();

                if (!$userr)
                {
                    return json_encode(["success" => 500, "message" => "Not Updated", ]);
                }

                return json_encode(["success" => 200, "message" => "Successfully Updated", "image" => $pro_imgage, ]);
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

    ///////Multiple upload image
  
    public function storeMultipleImages(Request $request)
    {
        if (!empty($_POST))
        {
            try
            {  
                $userr = $this
                    ->customer
                    ->getCustomerById($request["customer_id"]);

                    /*$check_img =  $this
                    ->customer
                    ->getImageNameByCustomerId($request["customer_id"]);*/

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
                ////to get url 
                    foreach ($files as $key => $getimg)
                    {
                        $a = trim($getimg, '[');
                        $b = trim($a, ']');
                        $getDetails1[] = URL::to('/') . "/public/uploads/img/" . trim($b, '"');
                    }
                if (!$userr)
                {
                    return json_encode(["success" => 500, "message" => "Not Updated", ]);
                }

                return json_encode(["success" => 200, "message" => "Successfully Uploaded", "image" => $files, "image_url" => $getDetails1 ]);
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
    ///////EnD
    // Terms and Condition-  Api Created by Naincy 17/12/21
    public function termsCondition()
    {
        $terms = $this
            ->terms
            ->getTermsData();
        if (!$terms)
        {
            return json_encode(["success" => 500, "message" => "No Data Found! ", ]);
        }
        else
        {
            return json_encode(["success" => 200, "message" => "Successfully", "terms_details" => $terms, ]);
        }
    }
    // Payment Terms -  Api Created by Naincy 14/04/2022
    public function paymentTerms()
    {
        $pay = $this
            ->paymenterms
            ->getpaymentTermsData();
        if (!$pay)
        {
            return json_encode(["success" => 500, "message" => "No Data Found! ", ]);
        }
        else
        {
            return json_encode(["success" => 200, "message" => "Successfully", "Payment_Terms_Details" => $pay, ]);
        }
    }
    // Get Review Api Created by Naincy 21/12/21
    public function reviewDetails(Request $request)
    {
        $rating = $request->rating;
        $review = $this
            ->review
            ->getReviewList($request);
        if (!$review)
        {
            return json_encode(["success" => 500, "message" => "No Data Found! ", ]);
        }
        else
        {
            return json_encode(["success" => 200, "message" => "Successfully", "review_Details" => $review, ]);
        }
    }
    ////// Accept Bid by Customer
    public function acceptBidByCustomer(Request $request)
    {
        $jobId = $request['job_id'];
        $bidId = $request['bid_id'];
        $driverId = $request['driver_id'];
        if ($request->job_id != '' && $request->bid_id != '' && $request->driver_id != '')
        {
            // check driver_id
            $driverId = $request->driver_id;
            $checkDriver = $this
                ->user_driver
                ->getDriverById($driverId);
            if (!$checkDriver)
            {
                return response()->json(['success' => 500, 'message' => 'Driver ID Does Not Exist']);
            }
            // check job_id
            $jobId = $request->job_id;
            $checkjobId = $this
                ->listing
                ->getListingById($jobId);
            if (!$checkjobId)
            {
                return response()->json(['success' => 500, 'message' => 'Job ID Does Not Exist']);
            }
            if ($driverId != '' && $jobId != '' && $bidId != '')
            {
                $data = $this
                    ->listing
                    ->updateAcceptBidStatus($request);
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'job_id : bid_id : driver_id is required'
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
            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'All Parameters Required : job_id , driver_id , bid_id']);
        }

    }

    //////Insert Review Detail
    public function storeReview(Request $request)
    {
        $post = $request->all();
        if (!empty($post))
        {
            $data = $this
                ->review
                ->storeReviewdata($request);
            if (!empty($data))
            {
                return response()->json(["success" => 200, "message" => "Review information saved successfully.", ]);
            }
            else
            {
                DB::rollback();
                return response()
                    ->json(["success" => 500, "message" => "Review information not saved", ]);
            }
        }
        else
        {
            DB::rollback();
            return response()
                ->json(["success" => 500, "message" => "All Parameters Required", ]);
        }
    }

    public function notificationList(Request $request)
    {
        $customerId = $request["customer_id"];
        $driverId = $request["driver_id"];
        $list = $this
            ->notificationlist
            ->getNotificationList();
        if (!$list)
        {
            return json_encode(["success" => 500, "message" => "No Data Found! ", ]);
        }
        else
        {
            return json_encode(["success" => 200, "message" => "Successfully", "Notificatoion_List " => $list, ]);
        }
    }

    //////////////////
    public function myListing(Request $request)
    {
        $customerId = $request["customer_id"];
        $search_jobs = $request["search_jobs"];
        $list_type = $request["list_type"]; // 0 = Listing , 1 = Bids
        if ($customerId != NULL && $list_type != NULL)
        {
            $getDetails = array();
            $list = $this
                ->listing
                ->getmyListingCustomerId($customerId, $list_type, $search_jobs);
             // print_r($list);
             // die;
            //////get url for images
            foreach ($list as $key => $value)
            {
                $getDetails1 = array();
                if ($value->upload_photos == "[]" )
                { 
                   $getoneimg
                    = "";
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
                $getDetails[] = ['job_id'=>$value->job_id,'descriptive_title' => $value->descriptive_title, 'pick_up_location' => $value->pick_up_location , 'bid_status' => $value->bid_status,'bid_count' => $value->bid_count, 'drop_off_location' => $value->drop_off_location,'add_bonus'=> $value->add_bonus, 'estimate_price' => $value->estimate_price, 'express_listing' => $value->express_listing, 'job_status' => $value->job_status,'upload_photos' => $getoneimg];
            }
            return response()->json(["success" => 200, "message" => "Success", "job_list" => $getDetails, ]);
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Customer ID and List Type is Required ", ]);
        }
    }

    public function myDeliveries(Request $request)
    {
        $customerId = $request["customer_id"];
        $search_jobs = $request["search_jobs"];
        $list_type = $request["list_type"]; // 0 = Active , 4 = Completed , 5 = Cancelled
              $getDetails = array(); 
        if ($customerId != "")
        {
            $list = $this
                ->listing
                ->getmyDeliveriesCustomerId($customerId, $list_type, $search_jobs);
     
           if(!empty($list))
           {
               
          
        //////get url for images
            foreach ($list as $key => $value)
            {
           
                if ($value->upload_photos == "[]" )
                { 
                   $getoneimg
                    = "";
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
               $getDetails[] = ['job_id'=>$value->job_id,'descriptive_title' => $value->descriptive_title, 'pick_up_location' => $value->pick_up_location , 'drop_off_location' => $value->drop_off_location,'estimate_price'=> $value->estimate_price, 'express_listing' => $value->express_listing,'bid_status' => $value->bid_status,'upload_photos' => $getoneimg ];
               /* $getDetails[] = ['job_id'=>$value->job_id,'descriptive_title' => $value->descriptive_title, 'pick_up_location' => $value->pick_up_location , 'drop_off_location' => $value->drop_off_location,'estimate_price'=> $value->estimate_price, 'express_listing' => $value->express_listing, 'job_status' => $value->job_status,'bid_status' => $value->bid_status,'upload_photos' => $getoneimg ];*/
            }
             
           }

            return response()->json(["success" => 200, "message" => "Success", "job_list" => $getDetails]);
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Customer ID is Required", ]);
        }
    }

    //////////////My deliveries for web 

    public function myDeliveriesweb(Request $request)
    {
        $customerId = $request["customer_id"];
        $search_jobs = $request["search_jobs"];
        $list_type = $request["list_type"]; // 0 = Active , 4 = Completed , 5 = Cancelled
        if ($customerId != "")
        {
            $list = $this
                ->listing
                ->getmyDeliveriesCustomerId($customerId, $list_type, $search_jobs);
            return response()->json(["success" => 200, "message" => "Success", "job_list" => $list, ]);
        }
        else
        {
            return json_encode(["success" => 500, "message" => "Customer ID is Required", ]);
        }
    }
    //////get job details by job id
    public function getJobDetailByJobId(Request $request)
    {
        $jobId = $request["job_id"];
        if ($jobId != '')
        {
            $detail = $this
                ->listing
                ->getJobDetailById($jobId);
            if (!empty($detail))
            {
                $detail = $this
                    ->listing
                    ->viewJobListDetails($jobId);

                    foreach ($detail as $key => $value)
            {
               
                $getDetails1 = array();
                if ($value->upload_photos != ' ')
                {
                    $img = explode(',', $value->upload_photos);
                    foreach ($img as $key => $getimg)
                    {
                        $a = trim($getimg, '[');
                        $b = trim($a, ']');
                        $getDetails1[] = URL::to('/') . "/public/uploads/img/" . trim($b, '"');
                    }
                }

                $getDetails = ['id'=>$value->id,'listing_id'=>$value->listing_id,'descriptive_title'=>$value->descriptive_title,'quantity_items' => $value->quantity_items, 'order_ref_number' => $value->order_ref_number, 'delivery_time' => $value->delivery_time, 'express_listing' => $value->express_listing, 'public_item_description' => $value->public_item_description, 'pick_up_location' => $value->pick_up_location,
                'drop_off_location'=>$value->drop_off_location,'express_delivery_rate'=>$value->express_delivery_rate,'parcel_size'=>$value->parcel_size,'available_person_name'=>$value->available_person_name,'available_person_contact'=>$value->available_person_contact,'available_person_email'=>$value->available_person_email,'pickup_contact_is_me'=>$value->pickup_contact_is_me,'pickup_anytime'=>$value->pickup_anytime,'private_information'=>$value->private_information,'to_time'=>$value->to_time,'from_time'=>$value->from_time,'from_date'=>$value->from_date,'to_date'=>$value->to_date,'driver_qualification'=>$value->driver_qualification,'receiving_contact_is_me'=>$value->receiving_contact_is_me,'receiver_name'=>$value->receiver_name,'receiver_contact'=>$value->receiver_contact,'receiver_email'=>$value->receiver_email,'deadline_id'=>$value->deadline_id,'delivery_date'=>$value->delivery_date,'drop_off_details'=>$value->drop_off_details,'is_template'=>$value->is_template,'template_name'=>$value->template_name
                ,'upload_photos' => $getDetails1 ];
            }
             return response()->json(["success" => 200, "message" => "Success", "job_list" => $getDetails ]);
            }
            else
            {
                return json_encode(array(
                    'success' => 500,
                    'message' => 'Data not Found'
                ));
            }
        }
        else
        {
            return json_encode(["success" => 500, "message" => " ID is Required", ]);
        }
    }
  
      ///=========================
       /* $datetime1 = new DateTime();
        $datetime2 = new DateTime($value['created_at']);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
        $vardate = date('Y-m-d',strtotime($value['created_at']));
        $getDAte = '' ;
        if($vardate === date('Y-m-d')){
        $getDAte = $interval->format(' %h hours ago');
        }else{
          $getDAte = $interval->format(' %a days ');
        }*/
        //===========================

     public function time_check(Request $request)
     {
            $job_id = $request["job_id"];
            $check_express = $this
            ->listing
            ->getExpressInfo($job_id);
       
        if($check_express->express_listing == 1)
          {
        $time = $check_express->job_post_time;

       // $time = date("m/d/Y h:i:s A T",$unixtime);

        $real_time = date("H:i:s", strtotime($time));
        $check_time   =  date("H:i:s", strtotime($real_time) + 60*60);
        return response()->json(["success" => 200, "message" => " successfully", 'time'=>$real_time ,'gettime'=> $check_time]);
         }
        else
          {
        DB::rollback();
        return response()
        ->json(["success" => 500, "message" => "Job id is not express", ]); 
         }
     }

      public function addTime(Request $request)
    {
      
        $currentDateTime = Carbon::now();
        
        $toDateTime = date("H:i A", strtotime($currentDateTime));
        $newDateTime = Carbon::now()->addHours(23);
        $tooDateTime = date("H:i A", strtotime($newDateTime));  
         
        print_r($toDateTime);
        echo "<br>";
        print_r($tooDateTime);
    }
    ///////////
    public function deleteListingByCustomerId(Request $request)
    {

          $customer_id = $request["customer_id"];

           $check_customer = $this
                ->listing
                ->checkCustomerInJobList($customer_id);
            if(isset($check_customer))
            {
                if($customer_id != $check_customer->customer_id )
                {
                   return response()->json(["success" => 500, "message" => "Please check Customer Id", ]);  
                }
                else
                {
                       $cal_time = $this ->listing
                                      ->getJobDetailByCustomerId($customer_id);
               
                    $data = $cal_time->job_post_time;
                    $toDateTime = date("Y-m-d H:i:s", $data);
                    $date = new DateTime($toDateTime);
                    $date1 = $date->add(new DateInterval('P1D'));
                    $date2=  $date1->format('Y-m-d H:i:s');

            print_r($toDateTime);
            print_r($date2);
                die;
          

                }
        
            }
            else
            {
                DB::rollback();
                return response()
                    ->json(["success" => 500, "message" => "Customer Id not found", ]);
            }
           
             $delData = $this
                ->listing
                ->removeJobListByCustomerId($request);

              if ($delData)
            {
                return response()->json(["success" => 200, "message" => "Successfully Job List Deleted", ]);
            }
            else
            {
                DB::rollback();
                return response()
                    ->json(["success" => 500, "message" => "Data not found", ]);
            }


           /* $cal_time = $this
                ->listing
                ->getJobDetailByCustomerId($customer_id);

            $data = $cal_time->job_post_time;
            $toDateTime = date("Y-m-d H:i:s", $data);
            $date = new DateTime($toDateTime);
            $date1 = $date->add(new DateInterval('P1D'));
            $date2=  $date1->format('Y-m-d H:i:s');

        print_r($toDateTime);
          print_r($date2);
          die;

           $delData = $this
                ->listing
                ->removeJobList($request);

              if ($delData)
            {
                return response()->json(["success" => 200, "message" => "Successfully Job List Deleted", ]);
            }
            else
            {
                DB::rollback();
                return response()
                    ->json(["success" => 500, "message" => "Data not found", ]);
            }
           */
           }
    ////End
    public function uploadVehicleInfo(Request $request)
    {
        $post = $request->all();

        if (!empty($post))
        {
            $check_Vinfo = $this
                ->vehicle
                ->getVehicleById($request["customer_id"]);

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
                return response()->json(["success" => 200, "message" => "Vehicle information saved successfully.", ]);
            }
            else
            {
                DB::rollback();
                return response()
                    ->json(["success" => 500, "message" => "Vehicle information not saved", ]);
            }
        }
        else
        {
            DB::rollback();
            return response()
                ->json(["success" => 500, "message" => "All Parameters Required", ]);
        }
    }
}
