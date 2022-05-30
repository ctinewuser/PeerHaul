<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $table = 'tbl_vehicle_type';
    protected $fillable = ['id','vehicle_name', 'created_at', 'updated_at'];
}