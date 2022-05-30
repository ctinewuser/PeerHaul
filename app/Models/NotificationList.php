<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationList extends Model
{
    protected $table = 'notification_list';
    protected $fillable = [ 'id', 'is_express', 'customer_id', 'driver_id', 'text', 'is_seen','created_at', 'updated_at'  ];
}