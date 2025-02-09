<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolDepartment extends Model {

	protected $table = 'school_department';
	protected $fillable = ['name','suffix','description'];

}
