<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    protected $table = 'popup_content';
    protected $fillable = [ 'id', 'title' ,'content'];
}