<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    
    protected $table = 'tbl_notifications';
    protected $fillable = [ 'id','from_user_id','to_user_id','message_type','message_text','news_id','created_at','updated_at' ];

}