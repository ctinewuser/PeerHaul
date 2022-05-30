<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'tbl_review';
    protected $fillable = [ 'id', 'driver_id', 'customer_id', 'total_stars', 'review_description', 'created_at', 'updated_at'  ];
}