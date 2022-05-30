<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddAccount extends Model
{
    protected $table = 'tbl_add_account_information';
    protected $fillable = [ 'id', 'driver_id','holder_name','branch_name','account_name','ifsc_code','created_at','update_date'];
}