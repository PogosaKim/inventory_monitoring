<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {

	protected $table = 'inventory';
	protected $fillable = ['inv_name_id', 'inv_unit','inv_quantity','date_purchase'];


}
