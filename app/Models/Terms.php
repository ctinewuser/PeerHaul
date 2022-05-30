<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    protected $table = 'tbl_terms_and_condition';
    protected $fillable = [ 'id', 'info'];
}