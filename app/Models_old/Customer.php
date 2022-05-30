<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'tbl_customer';
    protected $fillable = [ 'id', 'email', 'name', 'phone', 'password', 'profile_img', 'house_no','date_of_birth', 'fcmToken', 'created_at', 'updated_at'  ];
}