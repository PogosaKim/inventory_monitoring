<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {

	protected $table = 'inventory';
	protected $fillable = ['inv_name_id', 'inv_unit','inv_quantity','inv_brand','inv_desc','inv_amount','inv_total_amount','inv_location','barcode'];


}
