<?php 
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\PlaceBid;
use App\Models\PriceEstimator;
use App\User;
use DB;
use Illuminate\Support\Facades\Storage;
 
class BidRepository {

    public function __construct(PlaceBid $placeBid , PriceEstimator $priceEstimator ){
        $this->placeBid = $placeBid;
        $this->priceEstimator = $priceEstimator;
    }

    public function checkBid($id){
        return $this->priceEstimator->where('id',$id)->where('job_status','0')->where('bid_status','0')->first();
 
    }

    public function storeBid($request)
      {
     ///change
        $query = $this->priceEstimator->where('id', $request->job_id)->increment('bid_count', 1);
        //->update(['bid_count' => '']);
        $requestData = [ 'listing_id'=>$request->job_id , 'driver_id'=>$request->driver_id , 'your_bid'=>$request->your_bid , 'delivery_date'=>$request->delivery_date , 'delivery_time'=>$request->delivery_time ] ;
        $data = $this->placeBid->insertGetId($requestData) ;
        return $data ;

      }
     // public function getAcceptBidDetail($driver_id)
     // {
     //  return DB::table('tbl_job_bid')
     //     ->leftJoin('tbl_job_listing', 'tbl_job_bid.listing_id', '=', 'tbl_job_listing.id')

     // }
      public function getBidDetails($driver_id)
      {
        
      // DB::enableQueryLog();
     /////Detail of accept bid  
      return DB::table('tbl_job_bid')
            ->leftJoin('tbl_job_listing', 'tbl_job_bid.listing_id', '=', 'tbl_job_listing.id')
            ->leftJoin('tbl_item_information', 'tbl_job_bid.listing_id', '=', 'tbl_item_information.listing_id')
            ->where('tbl_job_bid.driver_id',$driver_id)
            ->where('tbl_job_listing.job_status','0')
            ->where('tbl_job_listing.bid_status','1')
            ->select('tbl_item_information.descriptive_title', 'tbl_item_information.upload_photos', 'tbl_job_listing.pick_up_location' , 'tbl_job_listing.drop_off_location' , 'tbl_job_listing.final_price')
            ->get();
            // dd(DB::getQueryLog());

      }

       public function getSingleBidDetail($driver_id,$job_id)
      {
        
       return DB::table('tbl_job_bid')
            ->leftJoin('tbl_job_listing', 'tbl_job_bid.listing_id', '=', 'tbl_job_listing.id')
            // ->leftJoin('tbl_item_information', 'tbl_job_bid.listing_id', '=', 'tbl_item_information.listing_id')
            ->leftJoin('tbl_driver_users', 'tbl_job_bid.driver_id', '=', 'tbl_driver_users.id')
            ->leftJoin('tbl_vehicle_info', 'tbl_job_bid.driver_id', '=', 'tbl_vehicle_info.driver_id')
            ->leftJoin('tbl_vehicle_type', 'tbl_vehicle_info.vechicle_type', '=', 'tbl_vehicle_type.id')
            
            ->where('tbl_job_bid.driver_id',$driver_id)
            ->where('tbl_job_bid.id',$job_id)
             
            ->select( 'tbl_job_listing.pick_up_location' , 'tbl_job_listing.drop_off_location' , 'tbl_job_listing.final_price' , 'tbl_vehicle_info.upload_vehicle_image' , 'tbl_vehicle_info.vechicle_type' , 'tbl_vehicle_info.vechicle_type' , 'tbl_vehicle_info.vechicle_make', 'tbl_job_bid.your_bid' , 'tbl_vehicle_type.vehicle_name' , 'tbl_driver_users.review_count' , 'tbl_driver_users.successful_deliveries', 'tbl_driver_users.name' , 'tbl_driver_users.profile_img' , 'tbl_driver_users.average_rating')
            ->first();

      }
      

}