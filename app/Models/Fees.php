<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{

    protected $table = 'tbl_fees_detail';
    protected $fillable = [ 'id' ,'parcel_id', 'parcel_fees','fees_per_km','fees_per_hr','service_fee' ,'peerHaul_fee','mileage_fees','route','route_per_km','created_at','updated_at'];

}