<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repository\CustomerRepository;
use App\Repository\ListingRepository;
use App\Models\PriceEstimator;
use App\Models\Template;


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

    public function __construct(CustomerRepository $customer, ListingRepository $listingRepository, ListingRepository $parcelsize, PriceEstimator $priceEstimator, ListingRepository $template)
    {
        $this->customer = $customer;
        $this->listing = $listingRepository;
        $this->parcelsize = $parcelsize;
        $this->priceEstimator = $priceEstimator;
        $this->template = $template;


    }

    public function getpriceEstimate(Request $request)
    {

        $pick_up_latitude = $request['pick_up_latitude'];
        $pick_up_longitute = $request['pick_up_longitute'];
        $drop_off_latitude = $request['drop_off_latitude'];
        $drop_off_longitute = $request['drop_off_longitute'];
        $parcel_size = $request['parcel_size'];

        
           
        return json_encode(array(
            'success' => 200,
            'message' => 'Successfully',
            'estimate_price' => "200"
        ));

    }

    ///////////////////Delete Job Listing
    public function deleteListing(Request $request)
    {
        $jobId = $request['job_id'];
        if ($jobId != '')
        {
            $delData = $this
                ->listing
                ->removeJobList($request);
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'job_id  is required'
            ));
        }

        if (!empty($delData))
        {
            return json_encode(array(
                'success' => 200,
                'message' => 'Data deleted successfully'
            ));
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'Data not found'
            ));
        }

    }

    ///////////  Delete image  //////////
    public function delImage()
    {
        $image_path = public_path("/uploads/testimg/165216536742.jpg");
        if (File::exists($image_path))
        {
            File::delete($image_path);
        }
    }
     ////////List of applied bid by particular job 
     public function getBidListById(Request $request) 
     {
         $bidId = $request['bid_id'];
       
         if ($bidId != '')
        {
           $data = $this->listing->getBidListById($bidId);
           if(!empty($data))
           {
                return response()->json(['success' => 200, 'message' => 'Success', 'details' => $data]);
           }
           else
           {
               DB::rollback();
            return response()->json(['success' => 500, 'message' => 'Data not found']);
           }
        
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'Id  is required'
            ));
        }  
     }

    ////END
   
    ////Update/Edit Job Listing
    public function edit_listing(Request $request)
    {
        if ($request->customer_id != '' && $request->pick_up_location != '' && $request->pick_up_latitude != '' && $request->pick_up_longitute != '' && $request->drop_off_location != '' && $request->drop_off_latitude != '' && $request->drop_off_longitute != '' && $request->parcel_size != '' && $request->estimate_price != '' && $request->express_listing != '' && $request->add_bonus != '' && $request->descriptive_title != '' && $request->size_of_entire_delivery != '' && $request->quantity_items != '' && $request->upload_photos != '' && $request->is_item_greater != '' && $request->public_item_description != '' && $request->order_ref_number != '' && $request->length != '' && $request->weight != '' && $request->width != '' && $request->height != '' && $request->available_person_name != '' && $request->available_person_contact != '' && $request->available_person_email != '' && $request->private_information != '' && $request->time != '' && $request->date != '' && $request->driver_qualification != '' && $request->receiver_name != '' && $request->receiver_contact != '' && $request->receiver_email != '' && $request->delivery_date != '' && $request->delivery_time != '' && $request->is_template != '' && $request->deadline != '')
        {

            $customerId = $request->customer_id;

            $checkCustomer = $this
                ->customer
                ->getCustomerById($customerId);

            if (!$checkCustomer)
            {
                return response()->json(['success' => 500, 'message' => 'Customer ID not exist']);
            }
            $listingUpdate = $this
                ->listing
                ->updatePriceEstimate($request);
            if ($listingUpdate)
            {
                return response()->json(['success' => 200, 'message' => 'List updated successfully']);

            }
            else
            {

                DB::rollback();
                return response()
                    ->json(['success' => 500, 'message' => 'List not updated ']);
            }
        }
        else
        {

            DB::rollback();
            return response()
                ->json(['success' => 500, 'message' => 'All Parameters Required : customer_id , pick_up_location , pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute , parcel_size , estimate_price , express_listing']);
        }
    }

    /////get detail by customer id
    public function getTemplateDetail(Request $request)
    {
        $customerId = $request['customer_id'];
        $data = $this
            ->template
            ->getTemplateName($customerId);

        if (!empty($data))
        {
            return json_encode(array(
                'success' => 200,
                'message' => 'Successfully',
                'template' => $data
            ));
        }
        else
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'No Data Found! '
            ));
        }

    }

    //=================================================
    public function getDeadlineByStatus(Request $request)
    {
        $status = $request['status'];

        $list = $this
            ->listing
            ->deadlineByJobId($status);

        if (!empty($list))
        {
            return response()->json(['success' => 200, 'message' => 'Success', 'list_detail' => $list, 'is_express' => $list[0]->is_express]);
        }
        else
        {
            DB::rollback();
            return response()
                ->json(['success' => 500, 'message' => 'Status required']);
        }

    }

    public function get_parcel_size(Request $request)
    {
        $data = $this
            ->parcelsize
            ->getParcelSize();
        if (!empty($data))
        {
            return response()->json(['success' => 200, 'message' => 'Success', 'parcel_size_list' => $data]);
        }
        else
        {
            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'Listing ID not exist']);
        }
    }

    /////////Check box for select template
    public function checkTemplate(Request $request)
    {
        $name = $request['template_name'];
        $customerId = $request['customer_id'];

        $data = $this
            ->template
            ->checkTemplateName($request);
        if ($data)
        {
            return json_encode(array(
                'success' => 500,
                'message' => 'Template name already exist '
            ));
        }
        else
        {

            return json_encode(array(
                'success' => 200,
                'message' => 'Success'
            ));
        }

    }
    ////get job detail by job id in behalf of temp id
    public function templateDataByTempId(Request $request)
    {

        $t_id = $request['template_id'];
        $detail = $this
            ->template
            ->getTempDataByJobId($t_id);

        if (!empty($detail))
        {

            $data1['item_information'] = ['descriptive_title' => $detail->descriptive_title, 'quantity_items' => $detail->quantity_items, 'is_item_greater' => $detail->is_item_greater, 'width' => $detail->width, 'height' => $detail->height, 'weight' => $detail->weight, 'length' => $detail->length, 'public_item_description' => $detail->public_item_description, 'order_ref_number' => $detail->order_ref_number, ];
            $data1['pickup_contact'] = ['receiver_name' => $detail->receiver_name, 'receiver_contact' => $detail->receiver_contact, 'receiver_email' => $detail->receiver_email, 'private_information' => $detail->private_information, 'pickup_contact_is_me' => $detail->pickup_contact_is_me, 'pickup_anytime' => $detail->pickup_anytime, ];
            $data1['delivery_information'] = ['driver_qualification' => $detail->driver_qualification, 'receiver_contact' => $detail->receiver_contact, 'receiver_email' => $detail->receiver_email, 'private_information' => $detail->private_information, 'receiver_name' => $detail->receiver_name, 'drop_off_details' => $detail->drop_off_details, 'deadline_id' => $detail->deadline_id, 'pickup_contact_is_me' => $detail->pickup_contact_is_me

            ];

            return response()
                ->json(['success' => 200, 'message' => 'Success', 'allData' => $data1]);
        }
        else
        {
            DB::rollback();
            return response()->json(['success' => 500, 'message' => 'Data not exist']);
        }
    }

    public function storeListingold(Request $request)
    {

        if ($request->customer_id != '' && 
            $request->pick_up_location != '' && $request->pick_up_latitude != '' && $request->pick_up_longitute != '' && $request->drop_off_location != '' && $request->drop_off_latitude != '' && $request->drop_off_longitute != '' 
            && $request->parcel_size != '' && $request->estimate_price != '' && $request->express_listing != '' && $request->add_bonus != '' && $request->descriptive_title != '' && $request->size_of_entire_delivery != '' && $request->quantity_items != '' 
            && $request->upload_photos != '' && $request->is_item_greater != '' && $request->public_item_description != ''  && $request->available_person_name != '' && $request->available_person_contact != '' 
            && $request->available_person_email != '' && $request->private_information != '' && $request->time != '' && $request->date != '' && $request->driver_qualification != '' && $request->receiver_name != '' && $request->receiver_contact != '' && $request->receiver_email != '' && $request->delivery_date != '' && $request->delivery_time != '' && $request->is_template != '' && $request->deadline != '')
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
                ->storeListingold($request);

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
                ->json(['success' => 500, 'message' => 'All Parameters Required : customer_id ,  pick_up_location ,  pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute ,  parcel_size , estimate_price , express_listing ,  add_bonus , descriptive_title , size_of_entire_delivery , quantity_items , upload_photos , is_item_greater , public_item_description , order_ref_number , length , weight , width , height , available_person_name , available_person_contact , available_person_email , private_information , time , date , driver_qualification , receiver_name , receiver_contact , receiver_email , delivery_date , delivery_time , is_template , deadline']);
        }

    }

    public function storeListing(Request $request)
    {

        if ($request->customer_id != '' && 
            $request->pick_up_location != '' && $request->pick_up_latitude != '' && $request->pick_up_longitute != '' && $request->drop_off_location != '' && $request->drop_off_latitude != '' && $request->drop_off_longitute != '' 
            && $request->parcel_size != '' && $request->estimate_price != '' && $request->express_listing != '' && $request->add_bonus != '' && $request->descriptive_title != '' && $request->size_of_entire_delivery != '' && $request->quantity_items != '' 
            && $request->upload_photos != '' && $request->is_item_greater != '' && $request->public_item_description != ''  && $request->available_person_name != '' && $request->available_person_contact != '' 
            && $request->available_person_email != '' && $request->private_information != '' && $request->time != '' && $request->date != '' && $request->driver_qualification != '' && $request->receiver_name != '' && $request->receiver_contact != '' && $request->receiver_email != '' && $request->delivery_date != '' && $request->delivery_time != '' && $request->is_template != '' && $request->deadline != '')
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
                ->json(['success' => 500, 'message' => 'All Parameters Required : customer_id ,  pick_up_location ,  pick_up_latitude , pick_up_longitute , drop_off_location , drop_off_latitude , drop_off_longitute ,  parcel_size , estimate_price , express_listing ,  add_bonus , descriptive_title , size_of_entire_delivery , quantity_items , upload_photos , is_item_greater , public_item_description , order_ref_number , length , weight , width , height , available_person_name , available_person_contact , available_person_email , private_information , time , date , driver_qualification , receiver_name , receiver_contact , receiver_email , delivery_date , delivery_time , is_template , deadline']);
        }

    }

}

