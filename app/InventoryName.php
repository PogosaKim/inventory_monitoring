<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryName extends Model {

	protected $table = 'inventory_name';
	protected $fillable = ['name', 'description'];

}
