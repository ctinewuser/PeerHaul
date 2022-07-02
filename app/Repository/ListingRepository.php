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
use App\Models\Fees;

use App\Models\DeliveryInformation;
use App\User;
use DB;
use File;
use Illuminate\Support\Facades\Storage;

class ListingRepository
{
    public function __construct(
        UserDriver $userDriver,
        VehicleType $vehicle_type,
        Customer $customer,
        Review $review,
        PriceEstimator $priceEstimator,
        ParcelSize $parcelsize,
        Terms $terms,
        Popup $popup,
        ItemInformation $itemInformation,
        PickupContact $pickupContact,
        DeliveryInformation $deliveryInformation,
        PaymentTerms $paymenterms,
        Template $template,
        DeadlineList $deadline,
        Bidjobs $bidjobs,
        AddAccount $account,
        Transaction $transaction,
        Fees $fees
    ) {
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
        $this->account = $account;
        $this->transaction = $transaction;
        $this->fees = $fees;
    }

    public function getListingById($id)
    {
        return $this->priceEstimator->where("id", $id)->first();
    }
    public function getPendingDelivery()
    {
        return $this->priceEstimator->where("job_status", "=", "0")->get();
    }

    public function getActiveDelivery()
    {
        return $this->priceEstimator->where("job_status", "=", "2")->get();
    }
    public function getTotalEstimatePrice()
    {   
        return DB::table("tbl_fees_detail")
            ->select("tbl_fees_detail.*")
            ->get();
    }
    public function getJobDetailList()
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_job_listing.*",
                "tbl_customer.name",
                "tbl_parcel_size.size_name AS parcel_name"
            )
            ->leftJoin(
                "tbl_customer",
                "tbl_customer.id",
                "=",
                "tbl_job_listing.customer_id"
            )
            ->leftJoin(
                "tbl_parcel_size",
                "tbl_parcel_size.id",
                "=",
                "tbl_job_listing.parcel_size"
            )
            ->get();
    }

    public function getJobDetailById($id)
    {
        return $this->priceEstimator->where("id", $id)->first();
    }
    public function getJobDetailByCustomerId($customer_id)
    {
        return $this->priceEstimator
            ->where("customer_id", $customer_id)
            ->first();
    }
    public function getJobDetailByDriverId($id)
    {
        return $this->priceEstimator->where("driver_id", $id)->first();
    }
    public function getAllVechileList()
    {
        return $this->vehicle_type->get();
    }
    public function getExpressInfo($job_id)
    {
        return $this->priceEstimator
            ->where("id", $job_id)

            ->first();
    }
    public function getDeadLine()
    {
        return $this->deadline->get();
    }

    public function getParcelList()
    {
        return $this->parcelsize->get();
    }

    public function getFeesList()
    {
        return $this->fees->get();
    }

    public function getFeesListnew()
    {
        return DB::table("tbl_fees_detail")
            ->select(
                "tbl_fees_detail.*",
                "tbl_parcel_size.size_name AS name",
                "tbl_fees_detail.parcel_id"
            )
            ->leftJoin(
                "tbl_parcel_size",
                "tbl_parcel_size.id",
                "=",
                "tbl_fees_detail.parcel_id"
            )
            ->orderBy("route", "ASC")
            ->get();
    }

    public function getReviewList($req)
    {
        return DB::table("tbl_review")
            ->where("total_stars", $req->rating)
            ->get();

        // return $this->review->where('total_stars','=',2)->get();
    }
    public function checkCustomerInJobList($customer_id)
    {
        return $this->priceEstimator
            ->where("customer_id", $customer_id)
            ->first();
    }
    public function getReviewListById($id)
    {
        return DB::table("tbl_review")
            ->where("driver_id", $id)
            ->get();
    }

    public function getReviewListAdmin($id)
    {
        return DB::table("tbl_review")
            ->select(
                "tbl_review.*",
                "tbl_driver_users.name AS drivername",
                "tbl_customer.name"
            )
            ->leftJoin(
                "tbl_customer",
                "tbl_customer.id",
                "=",
                "tbl_review.customer_id"
            )
            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_review.driver_id"
            )
            ->where("tbl_review.driver_id", $id)
            ->get();
    }
    public function removeDeadline($id)
    {
        return $this->deadline->where("id", $id)->delete();
    }
    public function getTemplateName($id)
    {
        //return $this->template->where('customer_id',$id)->first();
        return $this->template->where("customer_id", $id)->get();
    }
    public function storeTemplate($request)
    {
        $requestData = [
            "template_name" => $request->template_name,
            "job_id" => $request->listing_id,
            "customer_id" => $request->customer_id,
        ];
        $data = $this->template->insertGetId($requestData);

        return $data;
    }
    //////Add account detail
    public function addAccount($request)
    {
        //Note: Need to manage if account detail already exist for user
        $requestData = [
            "holder_name" => $request->holder_name,
            "branch_name" => $request->branch_name,
            "account_name" => $request->account_name,
            "ifsc_code" => $request->ifsc_code,
            "driver_id" => $request->driver_id,
        ];
        $data = $this->account->insertGetId($requestData);

        return $data;
    }
    //////// Saved Card Details
    public function cardDetails($request)
    {
        $requestData = ["driver_id" => $request->driver_id];
        return $this->account->where($requestData)->get();
    }
    //////Delete Card Detail
    public function delcardDetails($request)
    {
        $requestData = [
            "driver_id" => $request->driver_id,
            "id" => $request->card_id,
        ];
        return $this->account->where($requestData)->delete();
    }
    public function checkTemplateName($request)
    {
        return DB::table("tbl_template")
            //->where('customer_id', $request->customer_id)
            ->where("template_name", "like", trim($request->template_name))
            ->first();
    }

    public function getTempDataByJobId($t_id)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_job_listing.*",
                "tbl_item_information.*",
                "tbl_delivery_information.*",
                "tbl_template.template_name",
                "tbl_pickup_contact.*",
                "tbl_parcel_size.size_name AS parcel_size",
               
            )
            ->Join(
                "tbl_template",
                "tbl_job_listing.id",
                "=",
                "tbl_template.job_id"
            )
            ->Join(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->Join(
                "tbl_delivery_information",
                "tbl_delivery_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->Join(
                "tbl_pickup_contact",
                "tbl_pickup_contact.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->Join(
                "tbl_parcel_size",
                "tbl_parcel_size.id",
                "=",
                "tbl_job_listing.parcel_size"
            )
            ->where("tbl_template.id", "=", $t_id)
            ->first();
    }
    public function getJobBidDetail()
    {
        return DB::table("tbl_job_bid")
            ->select("tbl_job_bid.*", "tbl_driver_users.name")
            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_job_bid.driver_id"
            )

            ->get();

        //  return $this->bidjobs->get();
    }
    public function blockjob($id)
    {
        $requestData = ["job_status" => 7];
        return $this->priceEstimator->where("id", $id)->update($requestData);
    }
    public function getJobBidAdminDetail($id)
    {
        return DB::table("tbl_job_bid")
            ->select(
                "tbl_job_bid.*",
                "tbl_driver_users.name",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_job_bid.driver_id"
            )
            ->leftJoin(
                "tbl_job_listing",
                "tbl_job_listing.id",
                "=",
                "tbl_job_bid.listing_id"
            )
            ->where("tbl_job_bid.listing_id", $id)
            ->get();
    }
    public function getExpressListByJobId()
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_item_information.descriptive_title",
                "tbl_item_information.quantity_items",
                "tbl_item_information.upload_photos AS photos",
                "tbl_item_information.public_item_description",
                "tbl_item_information.order_ref_number",
                "tbl_pickup_contact.time_to_time AS pickup_time",
                "tbl_delivery_information.deadline_id",
                "tbl_delivery_information.delivery_time",
                "tbl_delivery_information.delivery_date",
                "tbl_job_listing.express_listing",
                "tbl_job_listing.pick_up_location",
                "tbl_job_listing.drop_off_location",
                "tbl_job_listing.estimate_price AS express_delivery_rate",
                "tbl_parcel_size.size_name AS parcel_size",
                "tbl_parcel_size.size_description",
                "delivery_deadline.taken_time"
            )
            ->leftjoin(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_pickup_contact",
                "tbl_job_listing.id",
                "=",
                "tbl_pickup_contact.listing_id"
            )
            ->leftJoin(
                "tbl_parcel_size",
                "tbl_parcel_size.id",
                "=",
                "tbl_job_listing.parcel_size"
            )
            ->leftJoin(
                "tbl_delivery_information",
                "tbl_delivery_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "delivery_deadline",
                "delivery_deadline.id",
                "=",
                "tbl_delivery_information.deadline_id"
            )
            ->where("tbl_job_listing.express_listing", 1)
            ->get();
    }
    public function getSuggestedBidList($driverId)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_item_information.descriptive_title",
                "tbl_item_information.quantity_items",
                "tbl_item_information.upload_photos AS photos",
                "tbl_item_information.order_ref_number",
                "tbl_job_listing.pick_up_location",
                "tbl_job_listing.drop_off_location",
                "tbl_parcel_size.size_name AS parcel_size",
                "tbl_job_bid.your_bid AS best_bid"
            )
            ->leftjoin(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_parcel_size",
                "tbl_parcel_size.id",
                "=",
                "tbl_job_listing.parcel_size"
            )
            ->leftJoin(
                "tbl_delivery_information",
                "tbl_delivery_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_job_bid",
                "tbl_job_bid.driver_id",
                "=",
                "tbl_job_listing.driver_id"
            )
            //->where('tbl_job_listing.driver_id',$driverId)
            ->where("tbl_job_listing.express_listing", 0)
            ->get();
    }

    public function getBidListById($bidId)
    {
        return DB::table("tbl_job_bid")

            ->select(
                "tbl_job_bid.driver_id",
                "tbl_job_bid.listing_id",
                "tbl_job_listing.estimate_price",
                "tbl_job_bid.your_bid",
                "tbl_driver_users.name",
                "tbl_driver_users.successful_deliveries",
                "tbl_vehicle_info.vechicle_model AS car_name",
                "tbl_vehicle_info.vechicle_type AS car_type",
                "tbl_review.total_stars",
                "tbl_vehicle_info.upload_vehicle_image",
                "tbl_fees_detail.service_fee",
                "tbl_fees_detail.peerHaul_fee"
            )

            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_job_bid.driver_id"
            )

            ->leftJoin(
                "tbl_review",
                "tbl_review.driver_id",
                "=",
                "tbl_job_bid.driver_id"
            )

            ->leftJoin(
                "tbl_vehicle_info",
                "tbl_vehicle_info.driver_id",
                "=",
                "tbl_job_bid.driver_id"
            )

            ->leftJoin(
                "tbl_fees_detail",
                "tbl_fees_detail.id",
                "=",
                "tbl_job_bid.driver_id"
            )

            ->leftJoin(
                "tbl_job_listing",
                "tbl_job_listing.driver_id",
                "=",
                "tbl_job_bid.driver_id"
            )

            ->where("tbl_job_bid.bid_accepted", 0)
            ->where("tbl_job_bid.id", $bidId)
            ->first();
    }

    public function updateContent($request)
    {
        $requestData = ["content" => $request->content];
        return $this->popup->where("id", $request->id)->update($requestData);
    }
    public function updateDeadline($request)
    {
        $requestData = [
            "type" => $request->type,
            "taken_time" => $request->taken_time,
        ];
        return $this->deadline->where("id", $request->id)->update($requestData);
    }
    public function updateFees($request)
    {
        $requestData = [
            "service_fee" => $request->service_fee,
            "peerHaul_fee" => $request->peerHaul_fee,
            "parcel_fees" => $request->parcel_fees,
            "fees_per_km" => $request->fees_per_km,
            "fees_per_hr" => $request->fees_per_hr,
        ];
        return $this->fees->where("id", $request->id)->update($requestData);
    }
    public function getBidListByJobId($jobId)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_job_bid.driver_id",
                "tbl_job_bid.listing_id",
                "tbl_job_listing.estimate_price",
                "tbl_job_bid.your_bid",
                "tbl_driver_users.name",
                "tbl_driver_users.successful_deliveries",
                "tbl_vehicle_info.vechicle_model AS car_name",
                "tbl_vehicle_info.vechicle_type AS car_type",
                "tbl_review.total_stars",
                "tbl_vehicle_info.upload_vehicle_image",
                "tbl_fees_detail.service_fee",
                "tbl_fees_detail.peerHaul_fee"
            )
            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin("tbl_review", "tbl_review.id", "=", "tbl_job_listing.id")
            ->leftJoin(
                "tbl_vehicle_info",
                "tbl_vehicle_info.driver_id",
                "=",
                "tbl_job_listing.driver_id"
            )
            ->leftJoin(
                "tbl_fees_detail",
                "tbl_fees_detail.id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_job_bid",
                "tbl_job_bid.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->where("bid_accepted", 0)
            ->where("listing_id", $jobId)
            ->get();
    }
    public function updateTerms($request)
    {
        $requestData = ["info" => $request->info];
        return $this->terms->where("id", 1)->update($requestData);
    }

    public function getTermsData()
    {
        return $this->terms->first();
    }

    public function getpaymentTermsData()
    {
        return $this->paymenterms->first();
    }

    public function getPopupData()
    {
        return $this->popup->get();
    }

    public function viewJobDetails($id)
    {
        return DB::table("tbl_job_listing")
            ->select("tbl_job_listing.*", "tbl_customer.name")
            ->leftJoin(
                "tbl_customer",
                "tbl_customer.id",
                "=",
                "tbl_job_listing.customer_id"
            )
            ->where("tbl_job_listing.id", $id)
            ->first();
    }

    public function changeDataById()
    {
        return DB::table("popup_content")
            ->select("popup_content.*")
            ->where("popup_content.id", $id)
            ->first();
    }
    public function changeDeadlineById()
    {
        return DB::table("delivery_deadline")
            ->select("delivery_deadline.*")
            ->where("delivery_deadline.id", $id)
            ->first();
    }
    public function changeFeesById()
    {
        return DB::table("tbl_fees_detail")
            ->select("tbl_fees_detail.*")
            ->where("tbl_fees_detail.id", $id)
            ->first();
    }
    public function oldviewJobListDetails($id)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_item_information.*",
                "tbl_job_listing.*",
                "tbl_delivery_information.*",
                "tbl_customer.name",
                "tbl_pickup_contact.*"
            )
            ->join(
                "tbl_customer",
                "tbl_job_listing.customer_id",
                "=",
                "tbl_customer.id"
            )
            ->leftJoin(
                "tbl_pickup_contact",
                "tbl_job_listing.id",
                "=",
                "tbl_pickup_contact.listing_id"
            )
            ->leftJoin(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_delivery_information",
                "tbl_delivery_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->where("tbl_job_listing.id", $id)
            ->first();
    }
    public function viewJobListDetails($jobId)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_item_information.descriptive_title",
                "tbl_item_information.quantity_items",
                "tbl_item_information.upload_photos",
                "tbl_delivery_information.delivery_time",
                "tbl_job_listing.express_listing",
                "tbl_item_information.public_item_description",
                "tbl_delivery_information.listing_id AS order_ref_number",
                "tbl_job_listing.pick_up_location",
                "tbl_job_listing.drop_off_location",
                "tbl_job_listing.estimate_price AS express_delivery_rate",
                "tbl_parcel_size.size_name AS parcel_size",
                "tbl_pickup_contact.*",
                "tbl_delivery_information.*"
            )
            ->leftjoin(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_pickup_contact",
                "tbl_job_listing.id",
                "=",
                "tbl_pickup_contact.listing_id"
            )
            ->leftJoin(
                "tbl_parcel_size",
                "tbl_parcel_size.id",
                "=",
                "tbl_item_information.size_of_entire_delivery"
            )
            ->leftJoin(
                "tbl_delivery_information",
                "tbl_delivery_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->where("tbl_job_listing.id", $jobId)
            // ->where('tbl_job_listing.job_status','=',6)
            ->get();
    }

    public function getPersonPickupDetail($jobId)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_pickup_contact.available_person_name",
                "tbl_pickup_contact.available_person_email",
                "tbl_pickup_contact.available_person_contact",
                "tbl_job_listing.pick_up_location",
                "tbl_job_listing.parcel_size",
                "tbl_pickup_contact.private_information"
            )
            ->leftJoin(
                "tbl_pickup_contact",
                "tbl_job_listing.id",
                "=",
                "tbl_pickup_contact.listing_id"
            )
            ->where("tbl_job_listing.id", $jobId)
            ->get();
    }
    public function getItemById($id)
    {
        return $this->itemInformation->where("id", $id)->first();
    }
    /////////Edit Pricesmtimator
    public function updatePriceEstimate($request)
    {
        /*$getoldvalue = $this->getJobDetailById($request->customer_id);
         $data = $getoldvalue;*/

        if ($request->pick_up_location != "") {
            $data["pick_up_location"] = $request->pick_up_location;
        }
        // print_r($request->pick_up_location);
        //     die;
        if ($request->parcel_size != "") {
            $data["parcel_size"] = $request->parcel_size;
        }
        if ($request->estimate_price != "") {
            $data["estimate_price"] = $request->estimate_price;
        }
        if ($request->parcel_size != "") {
            $data["parcel_size"] = $request->parcel_size;
        }
        if ($request->estimate_price != "") {
            $data["add_bonus"] = $request->add_bonus;
        }

        $requestData = [
            "customer_id" => $request->customer_id,
            "pick_up_location" => $request->pick_up_location,
            "pick_up_latitude" => $request->pick_up_latitude,
            "pick_up_longitute" => $request->pick_up_longitute,
            "drop_off_location" => $request->drop_off_location,
            "drop_off_latitude" => $request->drop_off_latitude,
            "drop_off_longitute" => $request->drop_off_longitute,
            "parcel_size" => $request->parcel_size,
            "estimate_price" => $request->estimate_price,
            "express_listing" => $request->express_listing,
            "add_bonus" => $request->add_bonus,
            "job_post_time" => time(),
        ];

        return $this->priceEstimator
            ->where("id", $request->job_id)
            ->update($requestData);
    }

    ////////Accept List By Customer
    public function updateAcceptBidStatus($request)
    {
        $requestData = [
            "bid_status" => 1,
            "driver_id" => $request->driver_id,
            "bid_id" => $request->bid_id,
        ];
        return $this->priceEstimator
            ->where("id", $request->job_id)
            ->update($requestData);
    }
    ////////Update Complete DropOff Status
    public function updateCompleteDropOffStatus($request)
    {
        $requestData = ["job_status" => 3, "driver_id" => $request->driver_id];
        return $this->priceEstimator
            ->where("id", $request->job_id)
            ->update($requestData);
    }
    ////////Update Complete PickUp Status
    public function updateCompletePickUpStatus($request)
    {
        $requestData = ["job_status" => 2, "driver_id" => $request->driver_id];
        return $this->priceEstimator
            ->where("id", $request->job_id)
            ->update($requestData);
    }
    //////////Cancel Job 5=cancelled
    public function updateCancelJobStatus($request)
    {
        $requestData = ["job_status" => 5, "driver_id" => $request->driver_id];
        return $this->priceEstimator
            ->where("id", $request->job_id)
            ->update($requestData);
    }

    /////Delete job list by id
    public function removeJobList($request)
    {
        $data = ["job_status" => 6];
        return DB::table("tbl_job_listing")
            ->where("id", $request->job_id)
            ->update($data);
    }

    public function removeJobListByCustomerId($customer_id)
    {
        return DB::table("tbl_job_listing")
            ->where("customer_id", $customer_id)
            ->delete();
    }
    public function deadlineByJobId($status)
    {
        return DB::table("delivery_deadline")
            ->select("id", "taken_time", "type AS is_express")
            ->where("type", $status)
            ->orderBy("taken_time", "DESC")
            ->get();
    }

    public function getParcelSize()
    {
        return DB::table("tbl_parcel_size")
            ->select(
                "id",
                DB::raw(
                    'CONCAT(size_name,"(",size_description,")") as size_name'
                )
            )
            ->get();
    }
    public function getParcelSizeById($parcelId)
    {
        return DB::table("tbl_parcel_size")
            ->select("tbl_parcel_size.*")
            ->where("id", $parcelId)
            ->get();
    }
    /////////Transaction History
    public function getTransactionDetail()
    {
        /////   0=Recieved , 1=Pay to , 2=Pending
        return DB::table("tbl_transaction_history")
            ->select(
                "tbl_transaction_history.id",
                "tbl_transaction_history.driver_id",
                "tbl_driver_users.name",
                "tbl_transaction_history.amount",
                "tbl_transaction_history.transaction_id",
                "tbl_transaction_history.status AS payment_status",
                "tbl_transaction_history.credit_to",
                "tbl_item_information.descriptive_title",
                "tbl_driver_users.profile_img"
            )
            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_transaction_history.driver_id"
            )
            ->leftJoin(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_transaction_history.id"
            )
            ->get();
    }
    public function getTransactionAdmin()
    {
        /////   0=Recieved , 1=Pay to , 2=Pending
        return DB::table("tbl_transaction_history")
            ->select(
                "tbl_transaction_history.id",
                "tbl_transaction_history.driver_id",
                "tbl_driver_users.name",
                "tbl_transaction_history.amount",
                "tbl_transaction_history.transaction_id",
                "tbl_transaction_history.created_at",
                "tbl_transaction_history.status AS payment_status",
                "tbl_transaction_history.credit_to",
                "tbl_driver_users.profile_img"
            )
            ->leftJoin(
                "tbl_driver_users",
                "tbl_driver_users.id",
                "=",
                "tbl_transaction_history.driver_id"
            )

            ->get();
    }
    ////END
    public function storeReviewdata($request)
    {
        $requestData = [
            "driver_id" => $request->driver_id,
            "customer_id" => $request->customer_id,
            "total_stars" => $request->total_stars,
            "customer_id" => $request->customer_id,
            "review_description" => $request->review_description,
        ];

        $data = $this->review->insertGetId($requestData);

        return $data;
    }

    public function getAllJobsByDriverId($id)
    {
        return DB::table("tbl_job_listing")
            ->select(
                "tbl_item_information.*",
                "tbl_job_listing.*",
                "tbl_delivery_information.*",
                "tbl_customer.name",
                "tbl_pickup_contact.*"
            )
            ->join(
                "tbl_customer",
                "tbl_job_listing.customer_id",
                "=",
                "tbl_customer.id"
            )
            ->leftJoin(
                "tbl_pickup_contact",
                "tbl_job_listing.id",
                "=",
                "tbl_pickup_contact.listing_id"
            )
            ->leftJoin(
                "tbl_item_information",
                "tbl_item_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->leftJoin(
                "tbl_delivery_information",
                "tbl_delivery_information.listing_id",
                "=",
                "tbl_job_listing.id"
            )
            ->where("tbl_delivery_information.listing_id", $id)
            ->first();
    }

    public function getmyDeliveriesCustomerId(
        $id,
        $status = "",
        $search_jobs = ""
    ) {
        // 0 = Active
        //// where('tbl_job_listing.bid_status','=', 1)
        /// 4 = Completed
        //// where('tbl_job_listing.job_status','=', 4)
        //// 5 = Cancelled
        ////  where('tbl_job_listing.job_status','=', 5)
        if ($status == 0) {
            $query = DB::table("tbl_job_listing")
                ->select(
                    "tbl_job_listing.id AS job_id",
                    "tbl_item_information.descriptive_title",
                    "tbl_job_listing.pick_up_location",
                    "tbl_job_listing.drop_off_location",
                    "tbl_job_listing.estimate_price",
                    "tbl_job_listing.express_listing",
                    "tbl_job_listing.bid_status",
                    "tbl_item_information.upload_photos"
                )
                ->join(
                    "tbl_item_information",
                    "tbl_item_information.listing_id",
                    "=",
                    "tbl_job_listing.id"
                )
                ->where("tbl_job_listing.bid_status", "=", 1)
                ->where("tbl_job_listing.customer_id", $id)
                ->get();
        } else {
            $query = DB::table("tbl_job_listing")
                ->select(
                    "tbl_job_listing.id AS job_id",
                    "tbl_item_information.descriptive_title",
                    "tbl_job_listing.pick_up_location",
                    "tbl_job_listing.drop_off_location",
                    "tbl_job_listing.estimate_price",
                    "tbl_job_listing.express_listing",
                    "tbl_job_listing.bid_status",
                    "tbl_job_listing.job_status",
                    "tbl_item_information.upload_photos"
                )
                ->join(
                    "tbl_item_information",
                    "tbl_item_information.listing_id",
                    "=",
                    "tbl_job_listing.id"
                )
                ->where("tbl_job_listing.job_status", "=", 4)
                ->where("tbl_job_listing.customer_id", $id)
                ->get();
        }
        return $query;
    }

    public function getmyListingCustomerId($id, $status = "", $search_jobs = "")
    {
        // 0 = Listing , 1 = Bids
        if ($status == 1) {
            $query = DB::table("tbl_job_listing")
                ->select(
                    "tbl_job_listing.id AS job_id",
                    "tbl_item_information.descriptive_title",
                    "tbl_job_listing.pick_up_location",
                    "tbl_job_listing.drop_off_location",
                    "tbl_job_listing.estimate_price",
                    "tbl_job_listing.express_listing",
                    "tbl_job_listing.job_status"
                )
                ->join(
                    "tbl_item_information",
                    "tbl_item_information.listing_id",
                    "=",
                    "tbl_job_listing.id"
                )
                ->where("tbl_job_listing.bid_count", ">", 0)
                ->where("tbl_job_listing.job_status", "!=", 6)
                ->where("tbl_job_listing.customer_id", $id)
                ->get();
        } else {
            $query = DB::table("tbl_job_listing")
                ->select(
                    "tbl_job_listing.id AS job_id",
                    "tbl_item_information.descriptive_title",
                    "tbl_item_information.upload_photos",
                    "tbl_job_listing.pick_up_location",
                    "tbl_job_listing.drop_off_location",
                    "tbl_job_listing.add_bonus",
                    "tbl_job_listing.estimate_price",
                    "tbl_job_listing.express_listing",
                    "tbl_job_listing.job_status",
                    "tbl_job_listing.bid_status",
                    "tbl_job_listing.bid_count"
                )
                ->join(
                    "tbl_item_information",
                    "tbl_item_information.listing_id",
                    "=",
                    "tbl_job_listing.id"
                )

                //->join('tbl_delivery_information', 'tbl_delivery_information.listing_id', '=', 'tbl_job_listing.id')
                //  ->where('tbl_delivery_information.receiving_contact_is_me', '=', 0)
                ->where("tbl_job_listing.job_status", "=", 4)
                ->where("tbl_job_listing.bid_count", "=", 0)

                ->orderBy("tbl_job_listing.id", "DESC")
                ->where("tbl_job_listing.customer_id", $id)
                ->get();
        }

        return $query;
    }

    public function storePriceEstimate($request)
    {
        $bid_status = "O";
        $add_bonus = "";

        if ($request->express_listing == 1 && $request->add_bonus != null) {
            $bid_status = "1";
            $add_bonus = $request->add_bonus;
        } elseif (
            $request->express_listing == 0 &&
            $request->add_bonus == null
        ) {
            $bid_status = "0";
            $add_bonus = "";
        } else {
            // $bid_status = '0';
            //   $add_bonus = "";
            $bid_status = "1";
            $add_bonus = "";
        }
        // print_r($bid_status);
        // die;

        $requestData = [
            "customer_id" => $request->customer_id,
            "pick_up_location" => $request->pick_up_location,
            "pick_up_latitude" => $request->pick_up_latitude,
            "pick_up_longitute" => $request->pick_up_longitute,
            "drop_off_location" => $request->drop_off_location,
            "drop_off_latitude" => $request->drop_off_latitude,
            "drop_off_longitute" => $request->drop_off_longitute,
            "parcel_size" => $request->parcel_size,
            "estimate_price" => $request->estimate_price,
            "express_listing" => $request->express_listing,
            "add_bonus" => $add_bonus,
            "bid_status" => $bid_status,
            "job_post_time" => time(),
        ];

        $data = $this->priceEstimator->insertGetId($requestData);
        return $data;
    }

    public function storeItemInformation($request)
    {
        $files = [];

        if ($request->hasfile("upload_photos")) {
            foreach ($request->file("upload_photos") as $key => $image) {
                $name =
                    time() .
                    "" .
                    $key .
                    "." .
                    $image->getClientOriginalExtension();
                $destinationPath = public_path("/uploads/img/");
                $image->move($destinationPath, $name);
                $files[] = $name;
            }
        }
        $file = new File();
        $file->upload_photos = $files;
        $str_json = json_encode($files);

        if ($request->order_ref_number == null) {
            $order_ref_number = "0";
        } else {
            $order_ref_number = $request->order_ref_number;
        }
        if ($request->template_id == null) {
            $template_id = "0";
        } else {
            $template_id = $request->template_id;
        }
        if ($request->is_item_greater == "0") {
            $width = "0";
            $height = "0";
            $length = "0";
            $weight = "0";
        } elseif (
            $request->is_item_greater == "1" &&
            $request->width != null &&
            $request->length != null &&
            $request->height != null &&
            $request->weight != null
        ) {
            $width = $request->width;
            $height = $request->height;
            $length = $request->length;
            $weight = $request->weight;
        } else {
            $width = "0";
            $height = "0";
            $length = "0";
            $weight = "0";
        }

        $requestData = [
            "customer_id" => $request->customer_id,
            "listing_id" => $request->listing_id,
            "template_id" => $template_id,
            "descriptive_title" => $request->descriptive_title,
            "quantity_items" => $request->quantity_items,
            "upload_photos" => $str_json,
            "is_item_greater" => $request->is_item_greater,
            "width" => $width,
            "height" => $height,
            "weight" => $weight,
            "length" => $length,
            "public_item_description" => $request->public_item_description,
            "order_ref_number" => $order_ref_number,
        ];

        $check_list = $request->listing_id;
        $query = DB::table("tbl_item_information")
            ->select("tbl_item_information.*")
            ->where("tbl_item_information.listing_id", "=", $check_list)
            ->get();

        if (sizeof($query) > 0) {
            $data = $this->itemInformation
                ->where("listing_id", $requestData["listing_id"])
                ->update($requestData);
        } else {
            $data = $this->itemInformation->insertGetId($requestData);
        }

        return $data;
    }
    public function storePickupInformation($request)
    {
        $time_to_time = "";
        $date_to_date = "";
        if (
            $request->pickup_anytime == "1" &&
            $request->date_to_date != null &&
            $request->time_to_time != null
        ) {
            $time_to_time = $request->time_to_time;
            $date_to_date = $request->date_to_date;
        } elseif (
            $request->pickup_anytime == "0" &&
            $request->date_to_date == null &&
            $request->time_to_time == null
        ) {
            $time_to_time = "";
            $date_to_date = "";
        } else {
            $time_to_time = "";
            $date_to_date = "";
        }
        $requestData = [
            "listing_id" => $request->listing_id,
            "available_person_name" => $request->available_person_name,
            "available_person_contact" => $request->available_person_contact,
            "available_person_email" => $request->available_person_email,
            "private_information" => $request->private_information,
            "time_to_time" => $time_to_time,
            "date_to_date" => $date_to_date,
            "pickup_anytime" => $request->pickup_anytime,
        ];

        $check_list = $request->listing_id;
        $query = DB::table("tbl_pickup_contact")
            ->select("*")
            ->where("listing_id", "=", $check_list)
            ->get();

        if (sizeof($query) > 0) {
            $data = $this->pickupContact
                ->where("listing_id", $requestData["listing_id"])
                ->update($requestData);
        } else {
            $data = $this->pickupContact->insertGetId($requestData);
        }

        return $data;
    }

    public function deliveryInformation($request)
    {
        $deadline1 = "";
        $deadline2 = "";

        $customer_id = $this->priceEstimator
            ->where("id", $request->listing_id)
            ->first();
        $template_name = "";
        if ($request->is_template == 1) {
            if ($request->template_name != null) {
                $requestData4 = [
                    "template_name" => $request->template_name,
                    "job_id" => $request->listing_id,
                    "customer_id" => $customer_id->customer_id,
                ];
                $data = $this->template->insertGetId($requestData4);
            } else {
                $request->template_name = "";
            }
        } else {
            $request->template_name = "";
        }
        if ($request->deadline_id == 9 || $request->deadline_id == 10) {
            $deadline1 = "";
            $deadline2 = "";

            $requestData = [
                "listing_id" => $request->listing_id,
                "driver_qualification" => $request->driver_qualification,
                "receiver_name" => $request->receiver_name,
                "receiver_contact" => $request->receiver_contact,
                "receiver_email" => $request->receiver_email,
                "delivery_date" => $deadline1,
                "delivery_time" => $deadline2,
                "is_template" => $request->is_template,
                "template_name" => $request->template_name,
                "deadline_id" => $request->deadline,
                "drop_off_details" => $request->drop_off_details,
            ];

            $data = $this->deliveryInformation->insertGetId($requestData);
        } else {
            if (
                $request->delivery_time == null &&
                $request->delivery_date == null
            ) {
                $deadline1 = "";
                $deadline2 = "";
            } else {
                $deadline2 = $request->delivery_time;
                $deadline1 = $request->delivery_date;
            }

            $requestData = [
                "listing_id" => $request->listing_id,
                "driver_qualification" => $request->driver_qualification,
                "receiver_name" => $request->receiver_name,
                "receiver_contact" => $request->receiver_contact,
                "receiver_email" => $request->receiver_email,
                "delivery_date" => $deadline1,
                "delivery_time" => $deadline2,
                "is_template" => $request->is_template,
                "template_name" => $request->template_name,
                "deadline_id" => $request->deadline,
                "drop_off_details" => $request->drop_off_details,
            ];

            $check_list = $request->listing_id;
            $query = DB::table("tbl_delivery_information")
                ->select("*")
                ->where("listing_id", "=", $check_list)
                ->get();

            if (sizeof($query) > 0) {
                $data = $this->deliveryInformation
                    ->where("listing_id", $requestData["listing_id"])
                    ->update($requestData);
            } else {
                $data = $this->deliveryInformation->insertGetId($requestData);
            }
        }
        $requestDataForm = $this->priceEstimator
            ->where("id", $requestData["listing_id"])
            ->update(["form_satus" => 1, "job_status" => 4]);
        return $data;
    }
    //==============create listing

    /////////////////////
    public function storeListing($request)
    {
        $to_time = "";
        $to_date = "";
        $from_time = "";
        $from_date = "";
        $time_to_time = "";
        $date_to_date = "";

        // form 1 ======
        if ($request->add_bonus == null) {
            $request->add_bonus = "";
        }
      
        $requestData = [
            "customer_id" => $request->customer_id,
            "pick_up_location" => $request->pick_up_location,
            "pick_up_latitude" => $request->pick_up_latitude,
            "pick_up_longitute" => $request->pick_up_longitute,
            "drop_off_location" => $request->drop_off_location,
            "drop_off_latitude" => $request->drop_off_latitude,
            "drop_off_longitute" => $request->drop_off_longitute,
            "parcel_size" => $request->parcel_size,
            "estimate_price" => $request->estimate_price,
            "express_listing" => $request->express_listing,
            "add_bonus" => $request->add_bonus, 
            "job_post_time" => time(),
        ];

        $data = $jobId = $this->priceEstimator->insertGetId($requestData);
        $listingId = $data;
        
      
        // form 2 ======
         $template_id ="";

        if ($request->template_id == "") {
            $template_id = "0";
        } else {
            $template_id = $request->template_id;
        }
        if ($request->is_item_greater == "0") {
            $width = "0";
            $height = "0";
            $length = "0";
            $weight = "0";
        } else {
            $width = $request->width;
            $height = $request->height;
            $length = $request->length;
            $weight = $request->weight;
        }


        /////////**/
        $requestData1 = [
            "customer_id" => $request->customer_id,
            "listing_id" => $listingId,
            "template_id" => $template_id,
            "descriptive_title" => $request->descriptive_title,
            "size_of_entire_delivery" => $request->size_of_entire_delivery,
            "quantity_items" => $request->quantity_items,
            "upload_photos" => $request->upload_photos,
            "is_item_greater" => $request->is_item_greater,
            "width" => $width,
            "height" => $height,
            "weight" => $weight,
            "length" => $length,
            "public_item_description" => $request->public_item_description,
            "order_ref_number" => $request->order_ref_number,
        ];
        $data = $this->itemInformation->insertGetId($requestData1);
        // form 3 ======
        ///add
     
        if (
            $request->pickup_anytime == "0" &&
            $request->to_time != null &&
            $request->from_time != null &&
            $request->to_date != null &&
            $request->from_date != null 
        ) {
            $to_time = $request->to_time;
            $to_date = $request->to_date;
            $from_time = $request->from_time;
            $from_date = $request->from_date;
          

        } elseif (
            $request->pickup_anytime == "1" &&
            $request->to_time == null &&
            $request->from_time == null &&
            $request->to_date == null &&
            $request->from_date == null

        ) {
            $to_time = "";
            $to_date = "";
            $from_time = "";
            $from_date = "";
           
        } else {
            $to_time = "";
            $to_date = "";
            $from_time = "";
            $from_date = "";
           }

           $time_to_time = "";
           $date_to_date = "";
       

        $requestData2 = [
            "listing_id" => $listingId,
            "available_person_name" => $request->available_person_name,
            "available_person_contact" => $request->available_person_contact,
            "available_person_email" => $request->available_person_email,
            "private_information" => $request->private_information,
            "to_date" => $to_date,
            "from_date" => $from_date,
            "to_time" => $to_time,
            "from_time" => $from_time,
            "pickup_contact_is_me" => $request->pickup_contact_is_me,
            "pickup_anytime" => $request->pickup_anytime,
            "time_to_time"=>$time_to_time,
            "date_to_date"=>$date_to_date
        ];
        $data = $this->pickupContact->insertGetId($requestData2);
        // form 4 ======
        if ($request->deadline_id == 9 || $request->deadline_id == 10) {
            $deadline1 = $request->delivery_date;
            $deadline2 = $request->delivery_time;
        } else {
            if (
                $request->delivery_time == null &&
                $request->delivery_date == null
            ) {
                $deadline2 = "";
                $deadline1 = "";
            } else {
                $deadline1 = $request->delivery_date;
                $deadline2 = $request->delivery_time;
            }
        }
        if ($request->template_name == "") {
            $template_name = "-";
        } else {
            $template_name = $request->template_name;
        }

        $requestData3 = [
            "listing_id" => $listingId,
            "driver_qualification" => $request->driver_qualification,
            "receiver_name" => $request->receiver_name,
            "receiver_contact" => $request->receiver_contact,
            "receiver_email" => $request->receiver_email,
            "delivery_date" => $deadline1,
            "delivery_time" => $deadline2,
            "is_template" => $request->is_template,
            "template_name" => $template_name,
            "deadline_id" => $request->deadline_id,
            "drop_off_details" => $request->drop_off_details,
            "receiving_contact_is_me" => $request->receiving_contact_is_me,
        ];
        $data = $this->deliveryInformation->insertGetId($requestData3);

        $requestDataForm = $this->priceEstimator
            ->where("id", $requestData3["listing_id"])
            ->update(["job_status" => 4]);

        if ($request->is_template == 1) {
            $requestData4 = [
                "template_name" => $request->template_name,
                "job_id" => $jobId,
                "customer_id" => $request->customer_id,
            ];
            $data = $this->template->insertGetId($requestData4);
        }
        return $data;
    }
  /*  public function storeListingjune($request)
    {
        // form 1 ======
        if ($request->add_bonus == null) {
            $request->add_bonus = "";
        }
        $requestData = [
            "customer_id" => $request->customer_id,
            "pick_up_location" => $request->pick_up_location,
            "pick_up_latitude" => $request->pick_up_latitude,
            "pick_up_longitute" => $request->pick_up_longitute,
            "drop_off_location" => $request->drop_off_location,
            "drop_off_latitude" => $request->drop_off_latitude,
            "drop_off_longitute" => $request->drop_off_longitute,
            "parcel_size" => $request->parcel_size,
            "estimate_price" => $request->estimate_price,
            "express_listing" => $request->express_listing,
            "add_bonus" => $request->add_bonus,
            "job_post_time" => time(),
        ];
        $data = $jobId = $this->priceEstimator->insertGetId($requestData);
        $listingId = $data;

        // form 2 ======
        if ($request->template_id == "") {
            $template_id = "0";
        } else {
            $template_id = $request->template_id;
        }
        if ($request->is_item_greater == "0") {
            $width = "0";
            $height = "0";
            $length = "0";
            $weight = "0";
        } else {
            $width = $request->width;
            $height = $request->height;
            $length = $request->length;
            $weight = $request->weight;
        }
        $requestData1 = [
            "customer_id" => $request->customer_id,
            "listing_id" => $listingId,
            "template_id" => $template_id,
            "descriptive_title" => $request->descriptive_title,
            "size_of_entire_delivery" => $request->size_of_entire_delivery,
            "quantity_items" => $request->quantity_items,
            "upload_photos" => $request->upload_photos,
            "is_item_greater" => $request->is_item_greater,
            "width" => $width,
            "height" => $height,
            "weight" => $weight,
            "length" => $length,
            "public_item_description" => $request->public_item_description,
            "order_ref_number" => $request->order_ref_number,
        ];
        $data = $this->itemInformation->insertGetId($requestData1);
        // form 3 ======
        ///add
        $getDetails = [
            "id" => $value->id,
            "listing_id" => $value->listing_id,
            "descriptive_title" => $value->descriptive_title,
            "quantity_items" => $value->quantity_items,
            "order_ref_number" => $value->order_ref_number,
            "delivery_time" => $value->delivery_time,
            "express_listing" => $value->express_listing,
            "public_item_description" => $value->public_item_description,
            "pick_up_location" => $value->pick_up_location,
            "drop_off_location" => $value->drop_off_location,
            "express_delivery_rate" => $value->express_delivery_rate,
            "parcel_size" => $value->parcel_size,
            "available_person_name" => $value->available_person_name,
            "available_person_contact" => $value->available_person_contact,
            "available_person_email" => $value->available_person_email,
            "pickup_contact_is_me" => $value->pickup_contact_is_me,
            "pickup_anytime" => $value->pickup_anytime,
            "private_information" => $value->private_information,
            "to_time" => $value->to_time,
            "from_time" => $value->from_time,
            "from_date" => $value->from_date,
            "to_date" => $value->to_date,
            "driver_qualification" => $value->driver_qualification,
            "receiving_contact_is_me" => $value->receiving_contact_is_me,
            "receiver_name" => $value->receiver_name,
            "receiver_contact" => $value->receiver_contact,
            "receiver_email" => $value->receiver_email,
            "deadline_id" => $value->deadline_id,
            "delivery_date" => $value->delivery_date,
            "drop_off_details" => $value->drop_off_details,
            "is_template" => $value->is_template,
            "template_name" => $value->template_name,
            "upload_photos" => $getDetails1,
        ];

        ///add
        $to_time = "";
        $to_date = "";
        $from_time = "";
        $from_date = "";
        if (
            $request->pickup_anytime == "1" &&
            $request->to_time != null &&
            $request->from_time != null &&
            $request->to_date != null &&
            $request->from_date != null
        ) {
            $to_time = $request->to_time;
            $to_date = $request->to_date;
            $from_time = $request->from_time;
            $from_date = $request->from_date;
        } elseif (
            $request->pickup_anytime == "0" &&
            $request->to_time == null &&
            $request->from_time == null &&
            $request->to_date == null &&
            $request->from_date == null
        ) {
            $to_time = "";
            $to_date = "";
            $from_time = "";
            $from_date = "";
        } else {
            $to_time = "";
            $to_date = "";
            $from_time = "";
            $from_date = "";
        }

        $requestData2 = [
            "listing_id" => $listingId,
            "available_person_name" => $request->available_person_name,
            "available_person_contact" => $request->available_person_contact,
            "available_person_email" => $request->available_person_email,
            "private_information" => $request->private_information,
            "to_date" => $to_date,
            "from_date" => $from_date,
            "to_time" => $to_time,
            "from_time" => $from_time,
            "pickup_contact_is_me" => $request->pickup_contact_is_me,
            "pickup_anytime" => $request->pickup_anytime,
        ];
        $data = $this->pickupContact->insertGetId($requestData2);

        // form 4 ======
        if ($request->deadline_id == 9 || $request->deadline_id == 10) {
            $deadline1 = "";
            $deadline2 = "";
        } else {
            if (
                $request->delivery_time == null &&
                $request->delivery_date == null
            ) {
                $deadline1 = "";
                $deadline2 = "";
            } else {
                $deadline2 = $request->delivery_time;
                $deadline1 = $request->delivery_date;
            }
        }
        if ($request->template_name == "") {
            $template_name = "-";
        } else {
            $template_name = $request->template_name;
        }

        $requestData3 = [
            "listing_id" => $listingId,
            "driver_qualification" => $request->driver_qualification,
            "receiver_name" => $request->receiver_name,
            "receiver_contact" => $request->receiver_contact,
            "receiver_email" => $request->receiver_email,
            "delivery_date" => $deadline1,
            "delivery_time" => $deadline2,
            "is_template" => $request->is_template,
            "template_name" => $template_name,
            "deadline_id" => $request->deadline_id,
            "drop_off_details" => $request->drop_off_details,
            "receiving_contact_is_me" => $request->receiving_contact_is_me,
        ];
        $data = $this->deliveryInformation->insertGetId($requestData3);
        $requestDataForm = $this->priceEstimator
            ->where("id", $requestData3["listing_id"])
            ->update(["job_status" => 4]);
        if ($request->is_template == 1) {
            $requestData4 = [
                "template_name" => $request->template_name,
                "job_id" => $jobId,
                "customer_id" => $request->customer_id,
            ];
            $data = $this->template->insertGetId($requestData4);
        }
        return $data;
    }*/
}
