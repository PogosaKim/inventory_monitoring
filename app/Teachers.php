<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model {

	protected $table = 'teacher';
	protected $fillable = ['person_id', 'employee_id','school_department_id'];

}
