<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemInformation extends Model
{

    protected $table = 'tbl_item_information';
    protected $fillable = [ 'id' , 'customer_id' , 'listing_id' , 'template_id' , 'descriptive_title' ,	'size_of_entire_delivery' ,	'quantity_items' , 'upload_photos' , 'is_item_greater' ,
 'width' , 'height' , 'weight' , 'length','public_item_description' , 'order_ref_number' , 'created_at' ,	'updated_at' ];

}