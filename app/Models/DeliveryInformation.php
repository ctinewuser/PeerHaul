<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryInformation extends Model
{
    protected $table = 'tbl_delivery_information';
    protected $fillable = [ 'id', 'listing_id' , 'driver_qualification' , 'receiver_name' ,	'receiver_contact' , 'receiver_email' , 'deadline_delivery' , 'express_listing','delivery_date' , 'delivery_time' ,'template_name', 'created_at' , 'updated_at' ];
}