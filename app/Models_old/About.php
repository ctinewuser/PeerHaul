<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'tbl_privacy_policy';
    protected $fillable = ['id','about', 'privacyPolicy', 'termsConditions'];
}