<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceEstimator extends Model
{
    protected $table = 'tbl_job_listing';
    protected $fillable = [ 'id','customer_id' , 'pick_up_location' , 'pick_up_latitude' , 'pick_up_longitute' , 'drop_off_location' , 'drop_off_latitude' , 'drop_off_longitute' , 'parcel_size' , 'estimate_price' , 'express_listing' , 'job_status' , 'bid_status' , 'created_at' , 'updated_at' ];
}