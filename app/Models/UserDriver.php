<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDriver extends Model
  {
    protected $table = 'tbl_driver_users';
    protected $fillable = [ 'id', 'email', 'name', 'phone', 'password', 'profile_img', 'date_of_birth', 'fcmToken',  'latitude','longitude','created_at', 'updated_at' ];
  }