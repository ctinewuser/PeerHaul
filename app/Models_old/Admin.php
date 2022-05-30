<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'tbl_admin';
    protected $fillable = ['id','name', 'email', 'password', 'show_password', 'created_at', 'updated_at'];
}