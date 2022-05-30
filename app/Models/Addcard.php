<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addcard extends Model
{
    protected $table = 'tbl_add_cards';
    protected $fillable = [ 'id', 'driver_id','first_name','last_name   ','card_number','security_code','expiry_month','expiry_year','created_at','update_at'];
}
