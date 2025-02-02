<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model {

	protected $table = 'user_role';
	protected $fillable = ['name'];

}
