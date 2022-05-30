<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Template extends Model
{
    protected $table = 'tbl_template';
    protected $fillable = [ 'id', 'template_name', 'job_id','customer_id','created_at'];
}


