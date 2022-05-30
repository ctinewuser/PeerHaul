<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupContact extends Model
{
    protected $table = 'tbl_pickup_contact';
    protected $fillable = ['id','about', 'privacyPolicy', 'termsConditions'];
}