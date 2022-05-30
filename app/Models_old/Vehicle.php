<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    protected $table    = 'tbl_vehicle_info';
    protected $fillable = [ 'id' ,'driver_id', 'upload_vehicle_image' ,  'vechicle_make' , 'vechicle_model' , 'vechicle_license_plate' ,  'vechicle_color' , 'vechicle_type' , 'driver_license_front' , 'driver_license_back' , 'created_at' ,  'updated_at' ] ;

} 