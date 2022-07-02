<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repository\CustomerRepository;
use App\Repository\ListingRepository;
use App\Models\PriceEstimator;
use App\Models\Template;
use App\Models\Fees;
use URL;
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
    public function __construct(
        CustomerRepository $customer,
        ListingRepository $listingRepository,
        ListingRepository $parcelsize,
        PriceEstimator $priceEstimator,
        ListingRepository $template,
        ListingRepository $fees
    ) {
        $this->customer = $customer;
        $this->listing = $listingRepository;
        $this->parcelsize = $parcelsize;
        $this->priceEstimator = $priceEstimator;
        $this->template = $template;
        $this->fees = $fees;
    }

    public function getpriceEstimate(Request $request)
    {
        $pick_up_latitude = $request["pick_up_latitude"];
        $pick_up_longitute = $request["pick_up_longitute"];
        $drop_off_latitude = $request["drop_off_latitude"];
        $drop_off_longitute = $request["drop_off_longitute"];
        $parcel_size = $request["parcel_size"];

        return json_encode([
            "success" => 200,
            "message" => "Successfully",
            "estimate_price" => "200",
        ]);
    }
    public function getpriceEstimateold(Request $request)
    {
        $data = $this->fees->getTotalEstimatePrice();
      foreach($data as $nan){
            $route = $nan->route;
            $route = $nan->service_fee;
            $route = $nan->parcel_fees;
            $route = $nan->route;
            $route = $nan->route;
            $route = $nan->route;
            $route = $nan->route;
      }
        print_r($route);
           die;
        $a1 = $data[0]->parcel_fees;
        $a2 = $data[0]->fees_per_km;
        $a3 = $data[0]->fees_per_hr;
        $a4 = $data[0]->service_fee;
        $a5 = $data[0]->peerHaul_fee;
        $b = $a1 + $a2 + $a3 + $a4 + $a5;
        $total = round($b);

        return json_encode([
            "success" => 200,
            "message" => "Successfully",
            "estimate_price" => $total ,
        ]);
    }

    ///////////////////Delete Job Listing
    public function deleteListing(Request $request)
    {
        $jobId = $request["job_id"];
        if ($jobId != "") {
            $delData = $this->listing->removeJobList($request);
        } else {
            return json_encode([
                "success" => 500,
                "message" => "job_id  is required",
            ]);
        }

        if (!empty($delData)) {
            return json_encode([
                "success" => 200,
                "message" => "Data deleted successfully",
            ]);
        } else {
            return json_encode([
                "success" => 500,
                "message" => "Data not found",
            ]);
        }
    }

    ///////////  Delete image  //////////
    public function delImage()
    {
        $image_path = public_path("/uploads/testimg/165216536742.jpg");
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
    }
    ////////List of applied bid by particular job
    public function getBidListById(Request $request)
    {
        $bidId = $request["bid_id"];

        if ($bidId != "") {
            $data = $this->listing->getBidListById($bidId);
            if (!empty($data)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Success",
                    "details" => $data,
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "Data not found",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "Id  is required",
            ]);
        }
    }

    ////END

    ////Update/Edit Job Listing
    public function edit_listing(Request $request)
    {
        if (
            $request->customer_id != "" &&
            $request->pick_up_location != "" &&
            $request->pick_up_latitude != "" &&
            $request->pick_up_longitute != "" &&
            $request->drop_off_location != "" &&
            $request->drop_off_latitude != "" &&
            $request->drop_off_longitute != "" &&
            $request->parcel_size != "" &&
            $request->estimate_price != "" &&
            $request->express_listing != "" &&
            $request->add_bonus != "" &&
            $request->descriptive_title != "" &&
            $request->size_of_entire_delivery != "" &&
            $request->quantity_items != "" &&
            $request->upload_photos != "" &&
            $request->is_item_greater != "" &&
            $request->public_item_description != "" &&
            $request->order_ref_number != "" &&
            $request->length != "" &&
            $request->weight != "" &&
            $request->width != "" &&
            $request->height != "" &&
            $request->available_person_name != "" &&
            $request->available_person_contact != "" &&
            $request->available_person_email != "" &&
            $request->private_information != "" &&
            $request->time != "" &&
            $request->date != "" &&
            $request->driver_qualification != "" &&
            $request->receiver_name != "" &&
            $request->receiver_contact != "" &&
            $request->receiver_email != "" &&
            $request->delivery_date != "" &&
            $request->delivery_time != "" &&
            $request->is_template != "" &&
            $request->deadline != ""
        ) {
            $checkCustomer = $this->customer->getCustomerById(
                $request->customer_id
            );

            if (!$checkCustomer) {
                return response()->json([
                    "success" => 500,
                    "message" => "Customer ID not exist",
                ]);
            }

            $listingUpdate = $this->listing->updatePriceEstimate($request);

            if ($listingUpdate) {
                return response()->json([
                    "success" => 200,
                    "message" => "List updated successfully",
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "List not updated ",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" =>
                    " Parameters Required : customer_id , pick_up_location , pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute , parcel_size , estimate_price , express_listing",
            ]);
        }
    }

    /////get detail by customer id
    public function getTemplateDetail(Request $request)
    {
        $customerId = $request["customer_id"];
        $data = $this->template->getTemplateName($customerId);

        if (!empty($data)) {
            return json_encode([
                "success" => 200,
                "message" => "Successfully",
                "template" => $data,
            ]);
        } else {
            return json_encode([
                "success" => 500,
                "message" => "No Data Found! ",
            ]);
        }
    }

    //=================================================
    public function getDeadlineByStatus(Request $request)
    {
        $status = $request["status"];

        $list = $this->listing->deadlineByJobId($status);

        if (!empty($list)) {
            return response()->json([
                "success" => 200,
                "message" => "Success",
                "list_detail" => $list,
                "is_express" => $list[0]->is_express,
            ]);
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "Status required",
            ]);
        }
    }

    public function get_parcel_size(Request $request)
    {
        $data = $this->parcelsize->getParcelSize();
        if (!empty($data)) {
            return response()->json([
                "success" => 200,
                "message" => "Success",
                "parcel_size_list" => $data,
            ]);
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "Listing ID not exist",
            ]);
        }
    }

    /////////Check box for select template
    public function checkTemplate(Request $request)
    {
        $name = $request["template_name"];
        $customerId = $request["customer_id"];

        $data = $this->template->checkTemplateName($request);
        if ($data) {
            return json_encode([
                "success" => 500,
                "message" => "Template name already exist ",
            ]);
        } else {
            return json_encode([
                "success" => 200,
                "message" => "Success",
            ]);
        }
    }

    //////////////
    public function getBidListByJobId(Request $request)
    {
        $jobId = $request["job_id"];

        if ($jobId != "") {
            $data = $this->listing->getBidListByJobId($jobId);
            if (!empty($data)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Success",
                    "list" => $data,
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "Data not found",
                ]);
            }
        } else {
            return json_encode([
                "success" => 500,
                "message" => "job_id  is required",
            ]);
        }
    }
    ////get job detail by job id in behalf of temp id

    public function templateDataByTempId(Request $request)
    {
        $t_id = $request["template_id"];
        $detail = $this->template->getTempDataByJobId($t_id);

        if (!empty($detail)) {

             $data1["price_estimate"] = [
                "pick_up_location" => $detail->pick_up_location,
                "drop_off_location" => $detail->drop_off_location,
                 "pick_up_latitude" => $detail->pick_up_latitude,
                "pick_up_longitute" => $detail->pick_up_longitute,
                "drop_off_latitude" => $detail->drop_off_latitude,
                "drop_off_longitute" => $detail->drop_off_longitute,
                "parcel_size" => $detail->parcel_size,
                "estimate_price" => $detail->estimate_price,
                "job_status" => $detail->job_status,
                "bid_status" => $detail->bid_status
               
            ];



            $data1["item_information"] = [
                "descriptive_title" => $detail->descriptive_title,
                "quantity_items" => $detail->quantity_items,
                "is_item_greater" => $detail->is_item_greater,
                "width" => $detail->width,
                "height" => $detail->height,
                "weight" => $detail->weight,
                "length" => $detail->length,
                "public_item_description" => $detail->public_item_description,
                "order_ref_number" => $detail->order_ref_number,
            ];

            $data1["pickup_contact"] = [
                "available_person_name" => $detail->available_person_name,
                "available_person_contact" => $detail->available_person_contact,
                "available_person_email" => $detail->available_person_email,
                "private_information" => $detail->private_information,
                "pickup_contact_is_me" => $detail->pickup_contact_is_me,
                "pickup_anytime" => $detail->pickup_anytime,
                "to_time" => $detail->to_time,
                "to_date" => $detail->to_date,
                "from_time" => $detail->from_time,
                "from_date" => $detail->from_date,
            ];

            $data1["delivery_information"] = [
                "driver_qualification" => $detail->driver_qualification,
                "receiver_contact" => $detail->receiver_contact,
                "receiver_email" => $detail->receiver_email,
                "private_information" => $detail->private_information,
                "receiver_name" => $detail->receiver_name,
                "drop_off_details" => $detail->drop_off_details,
                "deadline_id" => $detail->deadline_id,
                "delivery_date" => $detail->delivery_date,
                "delivery_time" => $detail->delivery_time,
                "receiving_contact_is_me" => $detail->receiving_contact_is_me,
            ];

            return response()->json([
                "success" => 200,
                "message" => "Success",
                "allData" => $data1,
            ]);
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" => "Data not exist",
            ]);
        }
    }

    //////////////////////////////////////////////////////////
    public function create_listing(Request $request)
    {
        if (
            $request->customer_id != null &&
            $request->pick_up_location != null &&
            $request->pick_up_latitude != null &&
            $request->pick_up_longitute != null &&
            $request->drop_off_location != null &&
            $request->drop_off_latitude != null &&
            $request->drop_off_longitute != null &&
            $request->parcel_size != null &&
            $request->estimate_price != null &&
            $request->express_listing != null
        ) {
            // check customer_id
            $customerId = $request->customer_id;

            $checkCustomer = $this->customer->getCustomerById($customerId);

            if (!$checkCustomer) {
                return response()->json([
                    "success" => 500,
                    "message" => "Customer ID not exist",
                ]);
            }

            $listingCreate = $this->listing->storePriceEstimate($request);

            if (!empty($listingCreate)) {
                $listDetails = $this->listing->getListingById($listingCreate);

                return response()->json([
                    "success" => 200,
                    "message" => "Success",
                    "list_id" => $listDetails->id,
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "list Not Created",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" =>
                    "All Fields Required : customer_id , pick_up_location , pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute , parcel_size , estimate_price , express_listing",
            ]);
        }
    }

    
    public function add_item_information(Request $request)
    {
        if (
            $request->customer_id != null &&
            $request->listing_id != null &&
            $request->descriptive_title != null
        ) {
            // check customer_id
            $customerId = $request->customer_id;
            $listingId = $request->listing_id;

            $checkCustomer = $this->customer->getCustomerById($customerId);

            if (!$checkCustomer) {
                return response()->json([
                    "success" => 500,
                    "message" => "Customer ID not exist",
                ]);
            }

            $checkListing = $this->listing->getListingById($listingId);

            if (!$checkListing) {
                return response()->json([
                    "success" => 500,
                    "message" => "Listing ID not exist",
                ]);
            }

            $storeItemInformation = $this->listing->storeItemInformation(
                $request
            );

            if (!empty($storeItemInformation)) {
                $listDetails = $this->listing->getItemById(
                    $storeItemInformation
                );
                return response()->json([
                    "success" => 200,
                    "message" => "Success",
                    "item_id" => $listDetails->id,
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "item-info Not saved",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" =>
                    "All Fields Required : customer_id , listing_id , descriptive_title, quantity_items , upload_photos , is_item_greater , public_item_description , order_ref_number",
            ]);
        }
    }
    public function add_pickup_contact(Request $request)
    {
        if (
            $request->listing_id != null &&
            $request->available_person_name != null &&
            $request->available_person_contact != null &&
            $request->available_person_email != ""
        ) {
            // check listing_id
            $listingId = $request->listing_id;
            $checkListing = $this->listing->getListingById($listingId);

            if (!$checkListing) {
                return response()->json([
                    "success" => 500,
                    "message" => "Listing ID not exist",
                ]);
            }

            $storePickupInformation = $this->listing->storePickupInformation(
                $request
            );

            if (!empty($storePickupInformation)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Success",
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "list Not Created",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" =>
                    "All Fields Required : listing_id , available_person_name , available_person_contact , available_person_email , pickup_time_id  , private_information ",
            ]);
        }
    }

    public function add_delivery_details(Request $request)
    {
        if (
            $request->listing_id != "" &&
            $request->driver_qualification != "" &&
            $request->receiver_name != "" &&
            $request->receiver_contact != "" &&
            $request->receiver_email != "" &&
            $request->is_template != "" &&
            $request->deadline != ""
        ) {
            // check listing_id
            $listingId = $request->listing_id;

            $checkListing = $this->listing->getListingById($listingId);

            if (!$checkListing) {
                return response()->json([
                    "success" => 500,
                    "message" => "Listing ID not exist",
                ]);
            }

            $deliveryInformation = $this->listing->deliveryInformation(
                $request
            );

            if (!empty($deliveryInformation)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Deliver Information Stored Successfully",
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "list Not Created",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" =>
                    "All Fields Required : listing_id ,  driver_qualification ,  receiver_name ,  receiver_contact ,  receiver_email ,  delivery_date ,  delivery_time , is_template , deadline ",
            ]);
        }
    }

    //////////////////////////////////


 public function storeListing(Request $request)
{ 

   
    if ($request->customer_id != NULL 
        && 
    $request->pick_up_location != NULL && $request->pick_up_latitude != NULL && $request->pick_up_longitute != NULL && $request->drop_off_location != NULL && $request->drop_off_latitude != NULL && $request->drop_off_longitute != NULL && $request->add_bonus != NULL
    && $request->parcel_size != NULL && $request->estimate_price != NULL && $request->express_listing != NULL  && $request->descriptive_title != NULL && $request->size_of_entire_delivery !=NULL && $request->quantity_items != NULL 
    && $request->upload_photos != NULL && $request->is_item_greater != NULL && $request->public_item_description != NULL  && $request->available_person_name != NULL && $request->available_person_contact != NULL
    && $request->available_person_email != NULL && $request->private_information != NULL  && $request->driver_qualification != NULL && $request->receiver_name != NULL && $request->receiver_contact != NULL && $request->receiver_email != NULL && $request->is_template != NULL&& $request->deadline_id != NULL  && $request->receiving_contact_is_me    != NULL 
           )
    {

        // check customer_id
        $customerId = $request->customer_id;

        $checkCustomer = $this
            ->customer
            ->getCustomerById($customerId);
        
        if (!$checkCustomer)
        {
            return response()->json(['success' => 500, 'message' => 'Customer ID not exist']);
        }
       
        $listingCreate = $this
            ->listing
            ->storeListing($request);
       
        if (!empty($listingCreate))
        {

            return response()->json(['success' => 200, 'message' => 'Success ! Job listed']);

        }
        else
        {

            DB::rollback();
            return response()
                ->json(['success' => 500, 'message' => 'list Not Created']);
        }

    }
    else
    {

        DB::rollback();
        return response()
            ->json(['success' => 500, 'message' => 'All Fields Required : customer_id ,  pick_up_location ,  pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute ,  parcel_size , estimate_price , express_listing ,  add_bonus , descriptive_title , size_of_entire_delivery , quantity_items , upload_photos , is_item_greater , public_item_description , order_ref_number , length , weight , width , height , available_person_name , available_person_contact , available_person_email , private_information , time , date , driver_qualification , receiver_name , receiver_contact , receiver_email , delivery_date , delivery_time , is_template , deadline,pickup_contact_is_me']);
    }

}

    public function storeListingold(Request $request)
    {
        if (
            $request->customer_id != null &&
            $request->pick_up_location != null &&
            $request->pick_up_latitude != null &&
            $request->pick_up_longitute != null &&
            $request->drop_off_location != null &&
            $request->drop_off_latitude != null &&
            $request->drop_off_longitute != null &&
            $request->parcel_size != null &&
            $request->estimate_price != null &&
            $request->express_listing != null &&
            $request->descriptive_title != null &&
            $request->size_of_entire_delivery != null &&
            $request->quantity_items != null &&
            $request->upload_photos != null &&
            $request->is_item_greater != null &&
            $request->public_item_description != null &&
            $request->available_person_name != null &&
            $request->available_person_contact != null &&
            $request->available_person_email != null &&
            $request->private_information != null &&
            $request->driver_qualification != null &&
            $request->receiver_name != null &&
            $request->receiver_contact != null &&
            $request->receiver_email != null &&
            $request->is_template != null &&
            $request->deadline_id != null &&
            $request->receiving_contact_is_me != null &&
            $request->from_date != null &&
            $request->to_date != null &&
            $request->to_time != null &&
            $request->from_time != null
        ) {
            // check customer_id
            $customerId = $request->customer_id;

            $checkCustomer = $this->customer->getCustomerById($customerId);

            if (!$checkCustomer) {
                return response()->json([
                    "success" => 500,
                    "message" => "Customer ID not exist",
                ]);
            }

            $listingCreate = $this->listing->storeListing($request);
            //     print_r($listingCreate);
            // die;
            if (!empty($listingCreate)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Success ! Job listed",
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    "success" => 500,
                    "message" => "list Not Created",
                ]);
            }
        } else {
            DB::rollback();
            return response()->json([
                "success" => 500,
                "message" =>
                    "All Fields Required : customer_id ,  pick_up_location ,  pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute ,  parcel_size , estimate_price , express_listing ,  add_bonus , descriptive_title , size_of_entire_delivery , quantity_items , upload_photos , is_item_greater , public_item_description , order_ref_number , length , weight , width , height , available_person_name , available_person_contact , available_person_email , private_information , time , date , driver_qualification , receiver_name , receiver_contact , receiver_email , delivery_date , delivery_time , is_template , deadline,pickup_contact_is_me",
            ]);
        }
    }
}
