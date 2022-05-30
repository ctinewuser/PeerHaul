<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repository\CustomerRepository ;
use App\Repository\ListingRepository ;
use App\Models\PriceEstimator ;
use App\Models\Template ;

use DB;
use Hash;
use Auth;
use File;
use Image;
use Mail;
use Lang;
use Session;
use DateTime;

class ListingApiController extends Controller
 {

  public function __construct ( CustomerRepository $customer , ListingRepository $listingRepository , ListingRepository $parcelsize,PriceEstimator $priceEstimator ,ListingRepository $template)
  {
     $this->customer = $customer ;
     $this->listing = $listingRepository ;
       $this->parcelsize = $parcelsize ;
        $this->priceEstimator = $priceEstimator ;
          $this->template = $template;
    
  } 
 
  

   ///////////////////
  public function create_listing(Request $request){
         
      if($request->customer_id != '' &&  $request->pick_up_location != '' &&  $request->pick_up_latitude != '' && $request->pick_up_longitute != '' && $request->drop_off_location != '' && $request->drop_off_latitude != '' && $request->drop_off_longitute != '' &&  $request->parcel_size != '' && $request->estimate_price != '' && $request->express_listing != '' &&  $request->add_bonus != '' )  {
         
        // check customer_id
        $customerId = $request->customer_id ; 
         
        $checkCustomer = $this->customer->getCustomerById($customerId) ; 

         if(!$checkCustomer) 
           {
              return response()->json(['success' => 500,  'message' => 'Customer ID not exist']) ; 
           }

           $listingCreate = $this->listing->storePriceEstimate($request) ;

           if(!empty($listingCreate)) {
              
              $listDetails = $this->listing->getListingById( $listingCreate ) ;
               
              return response()->json(['success' => 200, 'message' => 'Success', 'list_id'=>$listDetails->id]);

          }else{

              DB::rollback();
              return response()->json(['success' => 500,  'message' => 'list Not Created']);
          }
         } else {

              DB::rollback();
              return response()->json(['success' => 500, 'message' => 'All Parameters Required : customer_id , pick_up_location , pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute , parcel_size , estimate_price , express_listing']);
      }
     
  }
  
  //////////////////////////
  public function add_item_information(Request $request)
   {
         
    if( $request->customer_id !='' && $request->listing_id !='' && $request->descriptive_title !='' && $request->size_of_entire_delivery !='' && $request->quantity_items !='' && $request->upload_photos !='' && $request->is_item_greater !='' && $request->public_item_description !='' && $request->order_ref_number !='' && $request->length !='' && $request->weight !='' && $request->width !=''  && $request->height !='' ) {
       
      // check customer_id
      $customerId = $request->customer_id ;
      $listingId = $request->listing_id ; 
       
      $checkCustomer = $this->customer->getCustomerById($customerId) ; 

     
       if(!$checkCustomer) 
         {
            return response()->json(['success' => 500,  'message' => 'Customer ID not exist']) ; 
         }

      $checkListing = $this->listing->getListingById($listingId) ; 


         if(!$checkListing) 
           {
              return response()->json(['success' => 500,  'message' => 'Listing ID not exist']) ; 
           }   

         $storeItemInformation = $this->listing->storeItemInformation($request) ;
  
         if(!empty($storeItemInformation)) {
            
            $listDetails = $this->listing->getItemById( $storeItemInformation ) ;
            return response()->json(['success' => 200, 'message' => 'Success', 'item-id'=>$listDetails->id]);

         } else {

            DB::rollback();
            return response()->json(['success' => 500,  'message' => 'item-info Not saved']);
        }
       } else {

            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'All Parameters Required : customer_id , listing_id , descriptive_title, size_of_entire_delivery , quantity_items , upload_photos , is_item_greater , public_item_description , order_ref_number']);
     }
   
  }

  public function add_pickup_contact(Request $request)
   {
         
    if( $request->listing_id != '' && $request->available_person_name != '' && $request->available_person_contact != '' && $request->available_person_email != ''  && $request->private_information != '' && $request->time != '' && $request->date != '' )  {
       
      // check listing_id
      $listingId = $request->listing_id ; 
      $checkListing = $this->listing->getListingById($listingId) ; 
        
      if(!$checkListing) 
        {
          return response()->json(['success' => 500,  'message' => 'Listing ID not exist']) ; 
        }   

      $storePickupInformation = $this->listing->storePickupInformation($request) ;

        if(!empty($storePickupInformation)) {
                         
            return response()->json(['success' => 200, 'message' => 'Success']);

        }else{

            DB::rollback();
            return response()->json(['success' => 500,  'message' => 'list Not Created']);
        }
       } else {

            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'All Parameters Required : listing_id , available_person_name , available_person_contact , available_person_email , pickup_time_id  , private_information ']);
    }
   
  }

   
   public function add_delivery_details(Request $request)
   {

     if( $request->listing_id !='' && $request->driver_qualification !='' && $request->receiver_name !='' && $request->receiver_contact !='' && $request->receiver_email !='' && $request->delivery_date !='' && $request->delivery_time !='' && $request->is_template!=''  && $request->deadline!='')  {
       
      // check listing_id
      $listingId = $request->listing_id ; 
      $checkListing = $this->listing->getListingById($listingId) ;
      
      // $checkDelivery = $this->listing->getListingById($listingId) ;
        
      if(!$checkListing) 
        {
          return response()->json(['success' => 500,  'message' => 'Listing ID not exist']) ; 
        }   

      $deliveryInformation = $this->listing->deliveryInformation($request) ;

        if(!empty($deliveryInformation)) 
        {
              if($request->is_template == 1)
              {
                $storetemp = $this->template->storeTemplate($request);
              }       
            return response()->json(['success' => 200, 'message' => 'Success']);

        } else {

            DB::rollback();
            return response()->json(['success' => 500,  'message' => 'list Not Created']);
        }
       } else {

            DB::rollback();
            return response()->json(['success' => 500,
            'message' => 'All Parameters Required : listing_id ,  driver_qualification ,  receiver_name ,  receiver_contact ,  receiver_email ,  delivery_date ,  delivery_time , is_template , deadline ']);
    }
   
  }

///////////////////////////////////////Repository functions

  public function storePriceEstimate($request)
      {
        $requestData = [ 'customer_id'=>$request->customer_id , 'pick_up_location'=>$request->pick_up_location , 'pick_up_latitude'=>$request->pick_up_latitude , 'pick_up_longitute'=>$request->pick_up_longitute , 'drop_off_location'=>$request->drop_off_location , 'drop_off_latitude'=>$request->drop_off_latitude , 'drop_off_longitute'=>$request->drop_off_longitute , 'parcel_size'=>$request->parcel_size , 'estimate_price'=>$request->estimate_price , 'express_listing' =>$request->express_listing ,
          'add_bonus' =>$request->add_bonus , 'job_post_time' => time()] ;
        $data = $this->priceEstimator->insertGetId($requestData) ;
       
        return $data ;

      }
 public function storeItemInformation($request)
      {
        
        $requestData = [ 'customer_id' => $request->customer_id , 'listing_id' => $request->listing_id , 'template_id' => $request->template_id , 'descriptive_title' => $request->descriptive_title , 'size_of_entire_delivery' => $request->size_of_entire_delivery ,  'quantity_items' => $request->quantity_items , 'upload_photos' => $request->upload_photos , 'is_item_greater' => $request->is_item_greater , 'width' => $request->width , 'height' => $request->height , 'weight' => $request->weight , 'length' => $request->length , 'public_item_description' => $request->public_item_description , 'order_ref_number' => $request->order_ref_number ] ;

        $data = $this->itemInformation->insertGetId($requestData) ;
       
        return $data ; 

      }
       public function storePickupInformation($request)
      {
        $requestData = [ 'listing_id'=>$request->listing_id , 'available_person_name'=>$request->available_person_name , 'available_person_contact'=>$request->available_person_contact , 'available_person_email'=>$request->available_person_email , 'pickup_time_id'=>$request->pickup_time_id , 'private_information'=>$request->private_information , 'time_to_time'=>$request->time  , 'date_to_date'=>$request->date ,'pickup_contact_is_me'=>$request->pickup_contact_is_me,'pickup_anytime'=>$request->pickup_anytime] ;

        $data = $this->pickupContact->insertGetId($requestData) ;
       
        return $data ;

      }
        public function deliveryInformation($request)
      {
        
        $requestData = [ 'listing_id'=>$request->listing_id , 'driver_qualification'=> $request->driver_qualification , 'receiver_name'=> $request->receiver_name , 'receiver_contact'=> $request->receiver_contact ,'receiver_email'=> $request->receiver_email , 'delivery_date' => $request->delivery_date , 'delivery_time'=>$request->delivery_time,'is_template'=>$request->is_template , 'template_name'=>$request->template_name , 'deadline_id' => $request->deadline, 'drop_off_details' => $request->drop_off_details ] ;

        $data = $this->deliveryInformation->insertGetId($requestData) ;
       
        return $data ;

      }
    }


