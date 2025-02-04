<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\Person;
use App\RequestSupplies;
use App\Roles;
use App\Teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Teacher extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('teacher.index');
	}

	public function request()
	{
		$gen_user = Auth::user()->person_id;

		$person = Person::find($gen_user);
		// dd($person);
	
		$teacher = Teachers::where('person_id', $gen_user)
						  ->join('school_department','teacher.school_department_id','=','school_department.id')
						  ->select('teacher.id as teacher_id','school_department.name','school_department.suffix','school_department.id as school_department_id')
						  ->first();
	
		if ($teacher) {
			$role = Roles::where('id', 5)
             ->select('id', 'name')
             ->first();
		}



		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
		->select('inventory.id as inventory_id', 'inventory_name.name', 'inventory_name.description', 'inventory.inv_unit', 'inventory.inv_quantity')
		->where('inventory.inv_quantity', '!=', 0)
		->get();
		// dd($inventory_list);
	
	
	
		return view('teacher.request',compact('role','teacher','person','inventory_list'));
	}

	public function Createrequest()
	{
		$user_role_id = \Request::get('user_role_id');
		$date = \Request::get('date');
		$school_department_id = \Request::get('school_department_id');
		$inventory_ids = \Request::get('inventory_id');
		$request_quantities = \Request::get('request_quantity');
		
		try {
			foreach ($inventory_ids as $index => $inventoryId) {
				RequestSupplies::create([
					'inventory_id' => $inventoryId,  
					'requested_by' => Auth::user()->id,
					'user_role_id' => $user_role_id,
					'school_department_id' => $school_department_id,
					'date' => $date,
					'request_quantity' => $request_quantities[$index],
					'action_type' => 1
				]);
			}

			\DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Request submitted successfully!',
			]);

		} catch (\Exception $e) {
			\DB::rollBack();

			return response()->json([
				'success' => false,
				'message' => 'Failed to submit the request. Please try again.',
				'error' => $e->getMessage(),
			]);
		}
	}




	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
