<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeadlineList extends Model
{
    protected $table = 'delivery_deadline';
    protected $fillable = [ 'id', 'type' ,'taken_time'];
}