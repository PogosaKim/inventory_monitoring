<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestSupplies extends Model {

	protected $table = 'request_supplies';
	protected $fillable = ['inventory_id','requested_by','user_role_id','school_department_id','request_quantity','action_type','date','release_supplies_qty','is_request_purchase_order','is_purchase_order','inv_unit_price','inv_unit_total_price','request_supplies_code'];
}
