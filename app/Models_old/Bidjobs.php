<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidjobs extends Model
{
    protected $table = 'tbl_job_bid';
    protected $fillable = [ 'id', 'listing_id', 'driver_id','your_bid','delivery_date','delivery_time','created_at','updated_at'];

  
}