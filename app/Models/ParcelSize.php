<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelSize extends Model
{

    protected $table = 'tbl_parcel_size';
    protected $fillable = [ 'id' , 'size_name' ,'size_description'];

}