<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Dean extends Model {

	protected $table = 'dean';
	protected $fillable = ['person_id', 'employee_id','school_department_id'];

}
