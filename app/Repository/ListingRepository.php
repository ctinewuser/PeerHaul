<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\UserDriver;
use App\Models\VehicleType;
use App\Models\Customer;
use App\Models\Review;
use App\Models\Terms;
use App\Models\Popup;
use App\Models\DeadlineList;
use App\Models\PaymentTerms;
use App\Models\PriceEstimator;
use App\Models\ParcelSize;
use App\Models\ItemInformation;
use App\Models\PickupContact;
use App\Models\Template;
use App\Models\Bidjobs;
use App\Models\AddAccount;
use App\Models\Transaction;


use App\Models\DeliveryInformation;
use App\User;
use DB;
use File;
use Illuminate\Support\Facades\Storage;

class ListingRepository
{

    public function __construct(UserDriver $userDriver, VehicleType $vehicle_type, Customer $customer, Review $review, PriceEstimator $priceEstimator, ParcelSize $parcelsize, Terms $terms, Popup $popup, ItemInformation $itemInformation, PickupContact $pickupContact, DeliveryInformation $deliveryInformation, PaymentTerms $paymenterms, Template $template, DeadlineList $deadline, Bidjobs $bidjobs,AddAccount $account,Transaction $transaction)
    {
        $this->userDriver = $userDriver;
        $this->vehicle_type = $vehicle_type;
        $this->customer = $customer;
        $this->priceEstimator = $priceEstimator;
        $this->itemInformation = $itemInformation;
        $this->pickupContact = $pickupContact;
        $this->deliveryInformation = $deliveryInformation;
        $this->review = $review;
        $this->terms = $terms;
        $this->paymenterms = $paymenterms;
        $this->popup = $popup;
        $this->deadline = $deadline;
        $this->parcelsize = $parcelsize;
        $this->template = $template;
        $this->bidjobs = $bidjobs;
        $this->account =$account;
         $this->transaction =$transaction;
    }

    public function getListingById($id)
    {
        return $this
            ->priceEstimator
            ->where('id', $id)->first();
    }
         public function getPendingDelivery()
      {
      return $this->priceEstimator
      ->where('job_status','=','0')
      ->get();
    }

      public function getActiveDelivery()
      {
      return $this->priceEstimator
      ->where('job_status','=','2')
      ->get();
    }

    public function getJobDetailList()
    {

           return DB::table('tbl_job_listing')
            ->select('tbl_job_listing.*','tbl_customer.name')
           ->leftJoin('tbl_customer', 'tbl_customer.id', '=', 'tbl_job_listing.customer_id') 
            ->get();


        // return $this
        //     ->priceEstimator
        //     ->get();
    }

    public function getJobDetailById($id)
    {
        return $this
            ->priceEstimator
            ->where('id', $id)->first();
    }
    public function getJobDetailByDriverId($id)
    {
        return $this
            ->priceEstimator
            ->where('driver_id', $id)->first();
    }
    public function getAllVechileList()
    {
        return $this
            ->vehicle_type
            ->get();
    }

    public function getDeadLine()
    {
        return $this
            ->deadline
            ->get();
    }

    public function getParcelList()
    {
        return $this
            ->parcelsize
            ->get();
    }

    public function getReviewList($req)
    {
        return DB::table('tbl_review')->where('total_stars', $req->rating)
            ->get();

        // return $this->review->where('total_stars','=',2)->get();
        
    }
    
    public function getReviewListById($id)
    {
        return DB::table('tbl_review')->where('driver_id', $id)
            ->get();
 
    }
    
    public function getReviewListAdmin($id)
    {
         return DB::table('tbl_review')
            ->select('tbl_review.*', 'tbl_driver_users.name AS drivername','tbl_customer.name')
           ->leftJoin('tbl_customer', 'tbl_customer.id', '=', 'tbl_review.customer_id') 
            ->leftJoin('tbl_driver_users', 'tbl_driver_users.id', '=', 'tbl_review.driver_id')
            ->where('tbl_review.driver_id', $id)
            ->get();

    }
    
    public function getTemplateName($id)
    {
        //return $this->template->where('customer_id',$id)->first();
        return $this
            ->template
            ->where('customer_id', $id)->get();
    }
    public function storeTemplate($request)
    {

        $requestData = ['template_name' => $request->template_name, 'job_id' => $request->listing_id, 'customer_id' => $request->customer_id];
        $data = $this
            ->template
            ->insertGetId($requestData);

        return $data;
    }
    //////Add account detail
     public function addAccount($request)
    {
    //Note: Need to manage if account detail already exist for user 
        $requestData = ['holder_name' => $request->holder_name, 'branch_name' => $request->branch_name, 'account_name' => $request->account_name,'ifsc_code' => $request->ifsc_code,'driver_id' => $request->driver_id];
        $data = $this
            ->account
            ->insertGetId($requestData);

        return $data;
    }
    //////// Saved Card Details
    public function cardDetails($request)
    {
         $requestData = ['driver_id' => $request->driver_id];
      return $this
            ->account
            ->where($requestData)->get();
    }
    //////Delete Card Detail
    public function delcardDetails($request)
    {
      $requestData = ['driver_id' => $request->driver_id,'id'=>$request->card_id];
      return $this
            ->account
            ->where($requestData)->delete();
    }
    public function checkTemplateName($request)
    {
        return DB::table('tbl_template')
        //->where('customer_id', $request->customer_id)
        ->where('template_name', 'like', trim($request->template_name))
            ->first();
    }

    public function getTempDataByJobId($t_id)
    {

        return DB::table('tbl_job_listing')->select('tbl_job_listing.*', 'tbl_item_information.*', 'tbl_delivery_information.*', 'tbl_template.template_name', 'tbl_pickup_contact.*')
            ->Join('tbl_template', 'tbl_job_listing.id', '=', 'tbl_template.job_id')
            ->Join('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
            ->Join('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
            ->Join('tbl_pickup_contact', 'tbl_pickup_contact.listing_id', '=', 'tbl_job_listing.id')
            ->where('tbl_template.id', '=', $t_id)->first();

    }
    public function getJobBidDetail()
    {

        return DB::table('tbl_job_bid')
            ->select('tbl_job_bid.*', 'tbl_driver_users.name')
            ->leftJoin('tbl_driver_users', 'tbl_driver_users.id', '=', 'tbl_job_bid.driver_id')

            ->get();

        //  return $this->bidjobs->get();
        
    }
    public function blockjob($id)
    {
       $requestData = ['job_status' => 7];
        return $this
            ->priceEstimator
            ->where('id', $id)
            ->update($requestData);

       
    }
    public function getJobBidAdminDetail($id)
    {
           return DB::table('tbl_job_bid')
           ->select('tbl_job_bid.*','tbl_driver_users.name','tbl_job_listing.id')
           ->leftJoin('tbl_driver_users', 'tbl_driver_users.id', '=', 'tbl_job_bid.driver_id')
            ->leftJoin('tbl_job_listing', 'tbl_job_listing.id', '=', 'tbl_job_bid.listing_id')
            ->where('tbl_job_bid.listing_id',$id)
            ->get();
    }
    public function getExpressListByJobId()
    {
    return DB::table('tbl_job_listing')
    ->select('tbl_item_information.descriptive_title', 'tbl_item_information.quantity_items', 'tbl_item_information.upload_photos AS photos','tbl_item_information.order_ref_number','tbl_pickup_contact.time_to_time AS pickup_time','tbl_delivery_information.deadline_id','tbl_delivery_information.delivery_time','tbl_job_listing.express_listing',
        'tbl_job_listing.pick_up_location','tbl_job_listing.drop_off_location',
        'tbl_job_listing.estimate_price AS express_delivery_rate ',
        'tbl_parcel_size.size_name AS parcel_size')
    ->leftjoin('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
    ->leftJoin('tbl_pickup_contact', 'tbl_job_listing.id', '=', 'tbl_pickup_contact.listing_id')
    ->leftJoin('tbl_parcel_size', 'tbl_parcel_size.id', '=', 'tbl_item_information.size_of_entire_delivery')
    ->leftJoin('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
    ->where('tbl_job_listing.express_listing',1)
    ->get();

    }
    public function getSuggestedBidList($driverId)
    {
        return DB::table('tbl_job_listing')
    ->select('tbl_item_information.descriptive_title', 'tbl_item_information.quantity_items', 'tbl_item_information.upload_photos AS photos','tbl_item_information.order_ref_number',
    'tbl_job_listing.pick_up_location','tbl_job_listing.drop_off_location',
    'tbl_parcel_size.size_name AS parcel_size','tbl_job_bid.your_bid AS best_bid')
    ->leftjoin('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
    ->leftJoin('tbl_parcel_size', 'tbl_parcel_size.id', '=', 'tbl_item_information.size_of_entire_delivery')
    ->leftJoin('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
    ->leftJoin('tbl_job_bid', 'tbl_job_bid.driver_id', '=', 'tbl_job_listing.driver_id')
    ->where('tbl_job_listing.driver_id',$driverId)
    ->where('tbl_job_listing.express_listing',1)
    ->get();
    }

    public function getBidListById($bidId)
    {
   
    return DB::table('tbl_job_bid')

    ->select('tbl_job_bid.driver_id','tbl_job_bid.listing_id','tbl_job_listing.estimate_price','tbl_job_bid.your_bid','tbl_driver_users.name','tbl_driver_users.successful_deliveries','tbl_vehicle_info.vechicle_model AS car_name','tbl_vehicle_info.vechicle_type AS car_type','tbl_review.total_stars','tbl_vehicle_info.upload_vehicle_image','tbl_fees_detail.service_fee','tbl_fees_detail.peerHaul_fee') 

    ->leftJoin('tbl_driver_users', 'tbl_driver_users.id', '=', 'tbl_job_bid.driver_id')

    ->leftJoin('tbl_review', 'tbl_review.driver_id', '=', 'tbl_job_bid.driver_id')

    ->leftJoin('tbl_vehicle_info', 'tbl_vehicle_info.driver_id', '=', 'tbl_job_bid.driver_id')

    ->leftJoin('tbl_fees_detail', 'tbl_fees_detail.id', '=', 'tbl_job_bid.driver_id')

    ->leftJoin('tbl_job_listing', 'tbl_job_listing.driver_id', '=', 'tbl_job_bid.driver_id')

    ->where('tbl_job_bid.bid_accepted', 0)
    ->where('tbl_job_bid.id', $bidId)
    ->first();
    
    }
   
    public function updateContent($request)
    {
        $requestData = ['content' => $request->content];
        return $this
            ->popup
            ->where('id', $request->id)
            ->update($requestData);
    }

    public function updateTerms($request)
    {
        $requestData = ['info' => $request->info];
        return $this
            ->terms
            ->where('id', 1)
            ->update($requestData);
    }

    public function getTermsData()
    {
        return $this
            ->terms
            ->first();
    }

    public function getpaymentTermsData()
    {
        return $this
            ->paymenterms
            ->first();
    }

    public function getPopupData()
    {
        return $this
            ->popup
            ->get();

    }

    public function viewJobDetails($id)
    {

        return DB::table('tbl_job_listing')->select('tbl_job_listing.*', 'tbl_customer.name')
            ->leftJoin('tbl_customer', 'tbl_customer.id', '=', 'tbl_job_listing.customer_id')
            ->where('tbl_job_listing.id', $id)->first();

    }

    public function changeDataById()
    {
        return DB::table('popup_content')
            ->select('popup_content.*')
            ->where('popup_content.id', $id)->first();
    }

    public function oldviewJobListDetails($id)
    {

        return DB::table('tbl_job_listing')->select('tbl_item_information.*', 'tbl_job_listing.*', 'tbl_delivery_information.*', 'tbl_customer.name', 'tbl_pickup_contact.*')
            ->join('tbl_customer', 'tbl_job_listing.customer_id', '=', 'tbl_customer.id')
            ->leftJoin('tbl_pickup_contact', 'tbl_job_listing.id', '=', 'tbl_pickup_contact.listing_id')
            ->leftJoin('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
            ->leftJoin('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
            ->where('tbl_job_listing.id', $id)->first();
    }
    public function viewJobListDetails($jobId)
    {

         return DB::table('tbl_job_listing')
    ->select('tbl_item_information.descriptive_title', 'tbl_item_information.quantity_items', 'tbl_item_information.upload_photos AS photos','tbl_item_information.order_ref_number','tbl_item_information.upload_photos','tbl_delivery_information.delivery_time','tbl_job_listing.express_listing',
        'tbl_job_listing.pick_up_location','tbl_job_listing.drop_off_location',
        'tbl_job_listing.estimate_price AS express_delivery_rate',
        'tbl_parcel_size.size_name AS parcel_size')
    ->leftjoin('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
    ->leftJoin('tbl_pickup_contact', 'tbl_job_listing.id', '=', 'tbl_pickup_contact.listing_id')
    ->leftJoin('tbl_parcel_size', 'tbl_parcel_size.id', '=', 'tbl_item_information.size_of_entire_delivery')
    ->leftJoin('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
    ->where('tbl_job_listing.id',$jobId)
    ->get();
    }
    /*public function getStartJobDetail()
    {
       return DB::table('tbl_job_listing')
        ->select('tbl_job_listing.pick_up_location', 'tbl_pickup_contact.')
        ->leftJoin('tbl_pickup_contact', 'tbl_job_listing.id', '=', 'tbl_pickup_contact.listing_id')
        ->where('tbl_job_listing.id',$jobId)
        ->get();
       

    }*/
    public function getPersonPickupDetail($jobId)
    {
       return DB::table('tbl_job_listing') 
        ->select('tbl_pickup_contact.available_person_name','tbl_pickup_contact.available_person_email','tbl_pickup_contact.available_person_contact','tbl_job_listing.pick_up_location', 'tbl_job_listing.parcel_size','tbl_pickup_contact.private_information')
        ->leftJoin('tbl_pickup_contact', 'tbl_job_listing.id', '=', 'tbl_pickup_contact.listing_id')
        ->where('tbl_job_listing.id',$jobId)
        ->get();
    }
    public function getItemById($id)
    {
        return $this
            ->itemInformation
            ->where('id', $id)->first();
    }
    /////////Edit Pricesmtimator
    public function updatePriceEstimate($request)
    {
        /*$getoldvalue = $this->getJobDetailById($request->job_id);
        $data = $getoldvalue;
            print_r($data);
            die;
        if ($request->pick_up_location != '')
        {
            $data['pick_up_location'] = $request->pick_up_location;
        }
        if ($request->parcel_size != '')
        {
            $data['parcel_size'] = $request->parcel_size;
        }
        if ($request->estimate_price != '')
        {
            $data['estimate_price'] = $request->estimate_price;
        }
        if ($request->estimate_price != '')
        {
            $data['add_bonus'] = $request->add_bonus;
        }
        */
        $requestData = ['customer_id' => $request->customer_id, 'pick_up_location' => $request->pick_up_location, 'pick_up_latitude' => $request->pick_up_latitude, 'pick_up_longitute' => $request->pick_up_longitute, 'drop_off_location' => $request->drop_off_location, 'drop_off_latitude' => $request->drop_off_latitude, 'drop_off_longitute' => $request->drop_off_longitute, 'parcel_size' => $request->parcel_size, 'estimate_price' => $request->estimate_price, 'express_listing' => $request->express_listing, 'add_bonus' => $request->add_bonus, 'job_post_time' => time() ];
        $data = $this
            ->priceEstimator
            ->update($requestData);

        return $data;
    }

    ////////Accept List By Customer
    public function updateAcceptBidStatus($request)
    {
        $requestData = ['bid_status' => 1, 'driver_id' => $request->driver_id, 'bid_id' => $request->bid_id];
        return $this
            ->priceEstimator
            ->where('id', $request->job_id)
            ->update($requestData);
    }
    ////////Update Complete DropOff Status
     public function updateCompleteDropOffStatus($request)
     {
        $requestData = ['job_status' => 3, 'driver_id' => $request->driver_id];
        return $this
            ->priceEstimator
            ->where('id', $request->job_id)
            ->update($requestData);
    }
    ////////Update Complete PickUp Status
    public function updateCompletePickUpStatus($request)
    {
     $requestData = ['job_status' => 2, 'driver_id' => $request->driver_id];
        return $this
            ->priceEstimator
            ->where('id', $request->job_id)
            ->update($requestData);
    }
    //////////Cancel Job 5=cancelled
    public function updateCancelJobStatus($request)
    {
      $requestData = ['job_status' => 5, 'driver_id' => $request->driver_id];
        return $this
            ->priceEstimator
            ->where('id', $request->job_id)
            ->update($requestData);
    }
    
    /////Delete job list by id
    public function removeJobList($request)
    {

        $data = ['job_status' => 6];
        return DB::table('tbl_job_listing')->where('id', $request->job_id)
            ->update($data);
    }

    public function deadlineByJobId($status)
    {

        return DB::table('delivery_deadline')->select('id', 'taken_time', 'type AS is_express')
            ->where('type', $status)->get();

    }

    public function getParcelSize()
    {
        return DB::table('tbl_parcel_size')
            ->select('id', 'size_name')
            ->get();
    }
    /////////Transaction History 
    public function getTransactionDetail()
    {

        /////   0=Recieved , 1=Pay to , 2=Pending
       return DB::table('tbl_transaction_history')
       ->select('tbl_transaction_history.id','tbl_transaction_history.driver_id','tbl_driver_users.name','tbl_transaction_history.amount','tbl_transaction_history.transaction_id',
       'tbl_transaction_history.status AS payment_status','tbl_transaction_history.credit_to','tbl_item_information.descriptive_title','tbl_driver_users.profile_img')
       ->leftJoin('tbl_driver_users', 'tbl_driver_users.id', '=', 'tbl_transaction_history.driver_id')
       ->leftJoin('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_transaction_history.id')
       ->get();
    }

    ////END
    public function storeReviewdata($request)
    {
        $requestData = ['driver_id' => $request->driver_id, 'customer_id' => $request->customer_id, 'total_stars' => $request->total_stars, 'customer_id' => $request->customer_id, 'review_description' => $request->review_description];

        $data = $this
            ->review
            ->insertGetId($requestData);

        return $data;
    }

    public function getAllJobsByDriverId($id)
    {
        return DB::table('tbl_job_listing')->select('tbl_item_information.*', 'tbl_job_listing.*', 'tbl_delivery_information.*', 'tbl_customer.name', 'tbl_pickup_contact.*')
            ->join('tbl_customer', 'tbl_job_listing.customer_id', '=', 'tbl_customer.id')
            ->leftJoin('tbl_pickup_contact', 'tbl_job_listing.id', '=', 'tbl_pickup_contact.listing_id')
            ->leftJoin('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
            ->leftJoin('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
            ->where('tbl_delivery_information.listing_id', $id)->first();
    }

    public function getmyDeliveriesCustomerId($id, $status = '', $search_jobs = '')
    {
        // 0 = Active
        //// where('tbl_job_listing.bid_status','=', 1)
        /// 4 = Completed
        //// where('tbl_job_listing.job_status','=', 4)
        //// 5 = Cancelled
        ////  where('tbl_job_listing.job_status','=', 5)
        if ($status == 0)
        {
            $query = DB::table('tbl_job_listing')->select('tbl_job_listing.id AS job_id', 'tbl_item_information.descriptive_title', 'tbl_job_listing.pick_up_location', 'tbl_job_listing.drop_off_location', 'tbl_job_listing.estimate_price', 'tbl_job_listing.express_listing', 'tbl_job_listing.bid_status')
                ->join('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
                ->where('tbl_job_listing.bid_status', '=', 1)
                ->where('tbl_job_listing.customer_id', $id)->get();
        }
        else
        {
            $query = DB::table('tbl_job_listing')->select('tbl_job_listing.id AS job_id', 'tbl_item_information.descriptive_title', 'tbl_job_listing.pick_up_location', 'tbl_job_listing.drop_off_location', 'tbl_job_listing.estimate_price', 'tbl_job_listing.express_listing', 'tbl_job_listing.bid_status', 'tbl_job_listing.job_status')
                ->join('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
                ->where('tbl_job_listing.job_status', '=', 4)
                ->where('tbl_job_listing.customer_id', $id)->get();
        }
        return $query;
    }

    public function getmyListingCustomerId($id, $status = '', $search_jobs = '')

    {

        // 0 = Listing , 1 = Bids
        if ($status == 1)
        {

            $query = DB::table('tbl_job_listing')->select('tbl_job_listing.id AS job_id', 'tbl_item_information.descriptive_title', 'tbl_job_listing.pick_up_location', 'tbl_job_listing.drop_off_location', 'tbl_job_listing.estimate_price', 'tbl_job_listing.express_listing', 'tbl_job_listing.job_status')
                ->join('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
                ->where('tbl_job_listing.bid_count', '>', 0)
                ->where('tbl_job_listing.job_status', '!=', 6)
                ->where('tbl_job_listing.customer_id', $id)->get();

        }
        else
        {
           
            $query = DB::table('tbl_job_listing')
            ->select('tbl_job_listing.id AS job_id', 'tbl_item_information.descriptive_title','tbl_item_information.upload_photos', 'tbl_job_listing.pick_up_location', 'tbl_job_listing.drop_off_location', 'tbl_job_listing.estimate_price', 'tbl_job_listing.express_listing', 'tbl_job_listing.job_status')
                ->join('tbl_item_information', 'tbl_item_information.listing_id', '=', 'tbl_job_listing.id')
                ->where('tbl_job_listing.bid_count', '=', 0)
                ->where('tbl_job_listing.customer_id', $id)->get();


        }

        return $query;

    }



   /////

    //==============create listing
    

    public function storeListingold($request)
    {
        // form 1 ======
        $requestData = ['customer_id' => $request->customer_id, 'pick_up_location' => $request->pick_up_location, 'pick_up_latitude' => $request->pick_up_latitude, 'pick_up_longitute' => $request->pick_up_longitute, 'drop_off_location' => $request->drop_off_location, 'drop_off_latitude' => $request->drop_off_latitude, 'drop_off_longitute' => $request->drop_off_longitute, 'parcel_size' => $request->parcel_size, 'estimate_price' => $request->estimate_price, 'express_listing' => $request->express_listing, 'add_bonus' => $request->add_bonus, 'job_post_time' => time() ];
        $data = $jobId = $this
            ->priceEstimator
            ->insertGetId($requestData);

        $listingId = $data;
        /*
            print_r($listingId);
            print_r($data);
            die; */

        // form 2 ======  
       ///////////===================
        $requestData1 = ['customer_id' => $request->customer_id, 'listing_id' => $listingId, 'template_id' => $request->template_id, 'descriptive_title' => $request->descriptive_title, 'size_of_entire_delivery' => $request->size_of_entire_delivery, 'quantity_items' => $request->quantity_items,'upload_photos' => $request->upload_photos,'is_item_greater' => $request->is_item_greater, 'width' => $request->width, 'height' => $request->height, 'weight' => $request->weight, 'length' => $request->length, 'public_item_description' => $request->public_item_description, 'order_ref_number' => $request->order_ref_number];
        $data = $this
            ->itemInformation
            ->insertGetId($requestData1);
        // form 3 ======
        $requestData2 = ['listing_id' => $listingId, 'available_person_name' => $request->available_person_name, 'available_person_contact' => $request->available_person_contact, 'available_person_email' => $request->available_person_email, 'pickup_time_id' => $request->pickup_time_id, 'private_information' => $request->private_information, 'time_to_time' => $request->time, 'date_to_date' => $request->date, 'pickup_contact_is_me' => $request->pickup_contact_is_me, 'pickup_anytime' => $request->pickup_anytime];
        $data = $this
            ->pickupContact
            ->insertGetId($requestData2);
        // form 4 ======
        $requestData3 = ['listing_id' => $listingId, 'driver_qualification' => $request->driver_qualification, 'receiver_name' => $request->receiver_name, 'receiver_contact' => $request->receiver_contact, 'receiver_email' => $request->receiver_email, 'delivery_date' => $request->delivery_date, 'delivery_time' => $request->delivery_time, 'is_template' => $request->is_template, 'template_name' => $request->template_name, 'deadline_id' => $request->deadline, 'drop_off_details' => $request->drop_off_details];

        $data = $this
            ->deliveryInformation
            ->insertGetId($requestData3);
        if($request->is_template == 1)
        {
            $requestData4 = ['template_name' => $request->template_name, 'job_id' => $jobId, 'customer_id' => $request->customer_id];

            $data = $this
            ->template
            ->insertGetId($requestData4);

        }
       

        return $data;

    }


 /////////////////////
       public function storeListing($request)
    {
        // form 1 ======
        $requestData = ['customer_id' => $request->customer_id, 'pick_up_location' => $request->pick_up_location, 'pick_up_latitude' => $request->pick_up_latitude, 'pick_up_longitute' => $request->pick_up_longitute, 'drop_off_location' => $request->drop_off_location, 'drop_off_latitude' => $request->drop_off_latitude, 'drop_off_longitute' => $request->drop_off_longitute, 'parcel_size' => $request->parcel_size, 'estimate_price' => $request->estimate_price, 'express_listing' => $request->express_listing, 'add_bonus' => $request->add_bonus, 'job_post_time' => time() ];
        $data = $jobId = $this
            ->priceEstimator
            ->insertGetId($requestData);

        $listingId = $data;
    
        // form 2 ======  
               $files = [];
                if ($request->hasfile('upload_photos'))
                {
                    foreach ($request->file('upload_photos') as $file)
                    {
                        $name = time() . rand(1, 50) . '.' . $file->extension();
                        $destinationPath = public_path("/uploads/img/");
                        $file->move($destinationPath, $name);
                        $files[] = $name;
                    }
                }

                $file = new File();
                $file->upload_photos = $files;
            $str_json = json_encode($files); 
          
     ////Image Upload /////
    
    
    
        //////////=======================
         $requestData1 = ['customer_id' => $request->customer_id, 'listing_id' => $listingId, 'template_id' => $request->template_id, 'descriptive_title' => $request->descriptive_title, 'size_of_entire_delivery' => $request->size_of_entire_delivery, 'quantity_items' => $request->quantity_items, 'upload_photos' => $str_json, 'is_item_greater' => $request->is_item_greater, 'width' => $request->width, 'height' => $request->height, 'weight' => $request->weight, 'length' => $request->length, 'public_item_description' => $request->public_item_description, 'order_ref_number' => $request->order_ref_number];
        $data = $this
            ->itemInformation
            ->insertGetId($requestData1);
        // form 3 ======
        $requestData2 = ['listing_id' => $listingId, 'available_person_name' => $request->available_person_name, 'available_person_contact' => $request->available_person_contact, 'available_person_email' => $request->available_person_email, 'pickup_time_id' => $request->pickup_time_id, 'private_information' => $request->private_information, 'time_to_time' => $request->time, 'date_to_date' => $request->date, 'pickup_contact_is_me' => $request->pickup_contact_is_me, 'pickup_anytime' => $request->pickup_anytime];
        $data = $this
            ->pickupContact
            ->insertGetId($requestData2);
        // form 4 ======
        $requestData3 = ['listing_id' => $listingId, 'driver_qualification' => $request->driver_qualification, 'receiver_name' => $request->receiver_name, 'receiver_contact' => $request->receiver_contact, 'receiver_email' => $request->receiver_email, 'delivery_date' => $request->delivery_date, 'delivery_time' => $request->delivery_time, 'is_template' => $request->is_template, 'template_name' => $request->template_name, 'deadline_id' => $request->deadline, 'drop_off_details' => $request->drop_off_details];

        $data = $this
            ->deliveryInformation
            ->insertGetId($requestData3);
        if($request->is_template == 1)
        {
            $requestData4 = ['template_name' => $request->template_name, 'job_id' => $jobId, 'customer_id' => $request->customer_id];

            $data = $this
            ->template
            ->insertGetId($requestData4);

        }
       

        return $data;

    }


}

