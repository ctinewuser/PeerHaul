<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'tbl_transaction_history';
    protected $fillable = [ 'id', 'driver_id', 'amount', 'transaction_id', 'credit_to', 'pay_to','created_at', 'updated_at'  ];
}