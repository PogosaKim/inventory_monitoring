<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\Person;
use App\RequestSupplies;
use App\Roles;
use App\Teachers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

	public function my_request()
	{
		return view('teacher.my_request');
	}

	public function my_request_data()
{
    $gen_user = Auth::user()->id;
    $user = User::find($gen_user);

    $get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
        ->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
        ->join('users', 'request_supplies.requested_by', '=', 'users.id')
        ->join('person', 'users.person_id', '=', 'person.id')
        ->where('request_supplies.requested_by', $user->id)
        ->selectRaw("
            request_supplies.request_supplies_code,
			request_supplies.id as request_supplies_id,
            GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names,
            GROUP_CONCAT(DISTINCT FORMAT(request_supplies.inv_unit_price, 2) ORDER BY inventory_name.name ASC SEPARATOR ' / ') as inv_unit_prices,
            GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities,
            GROUP_CONCAT(FORMAT(request_supplies.inv_unit_total_price, 2) ORDER BY inventory_name.name ASC SEPARATOR ' / ') as inv_unit_total_prices,
            request_supplies.date
        ")
        ->groupBy('request_supplies.request_supplies_code', 'request_supplies.date')
        ->orderBy('request_supplies.updated_at', 'asc')
        ->get();
	// dd($get_request_supplies);

    $datatable = $get_request_supplies->map(function ($request) {
        return [
			'item' => '<a data-request_supplies_id="'.$request->request_supplies_id.'" data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
							style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
							class="viewDetail">'.$request->item_names.'</a>',
            'quantity' => $request->request_quantities, 
			'inv_unit_price' => '₱' . $request->inv_unit_prices, 
            'inv_unit_total_price' => '₱' . $request->inv_unit_total_prices, 
            'date' => Carbon::parse($request->date)->format('F j, Y'),
        ];
    });

    return response()->json($datatable);
}


public function my_request_data_form(){
	$request_supplies_id = \Request::get('request_supplies_id');
	$request_supplies_code = \Request::get('request_supplies_code');

	$gen_user = Auth::user()->person_id;

	$person = Person::find($gen_user);

	$teacher = Teachers::where('person_id', $gen_user)
	->join('school_department','teacher.school_department_id','=','school_department.id')
	->select('teacher.id as teacher_id','school_department.name','school_department.suffix','school_department.id as school_department_id')
	->first();

	$role = Roles::where('id', 3)
		->select('id', 'name')
		->first();


	$finance_head = Person::where('person.id',24)->first();
	$pc = Person::where('person.id',1)->first();

	$request_supplies = RequestSupplies::where('id', $request_supplies_id)->first();
	$release_date = $request_supplies->release_date ? : date('Y-m-d');
	$my_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
	->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
	->where('request_supplies.request_supplies_code', $request_supplies_code)
	->select('inventory_name.name', 'request_supplies.request_quantity', 'request_supplies.inv_unit_price', 'request_supplies.inv_unit_total_price','request_supplies.date')
	->get();
	// dd($)


	return view('teacher.my_request_form',compact('role','teacher','person','inventory_list','finance_head','pc','my_request_supplies'));
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
			$role = Roles::where('id', 3)
             ->select('id', 'name')
             ->first();
		}

		$finance_head = Person::where('person.id',24)->first();
		$pc = Person::where('person.id',1)->first();

		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
		->select('inventory.id as inventory_id', 'inventory_name.name', 'inventory_name.description', 'inventory.inv_unit', 'inventory.inv_quantity','inv_amount')
		->where('inventory.inv_quantity', '!=', 0)
		->get();
		
		// dd($inventory_list);
	
	
	
		return view('teacher.request',compact('role','teacher','person','inventory_list','finance_head','pc'));
	}

	public function Createrequest()
	{
		$user_role_id = \Request::get('user_role_id');
		$date = \Request::get('date');
		$school_department_id = \Request::get('school_department_id');
		$inventory_ids = \Request::get('inventory_id');
		$request_quantities = \Request::get('request_quantity');
		$inv_unit_price = \Request::get('inv_unit_price');
		$inv_unit_total_price = \Request::get('inv_unit_total_price');
		$request_supplies_code = Str::random(5);
		
		try {
			foreach ($inventory_ids as $index => $inventoryId) {
				RequestSupplies::create([
					'inventory_id' => $inventoryId,
					'requested_by' => Auth::user()->id,
					'user_role_id' => $user_role_id,
					'school_department_id' => $school_department_id,
					'date' => $date,
					'request_quantity' => $request_quantities[$index],
					'action_type' => 1 ,
					'inv_unit_price' => $inv_unit_price,
					'inv_unit_total_price' => $inv_unit_total_price,
					'request_supplies_code' => $request_supplies_code
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

	public function GetTrackingRequest()
	{
		$gen_user = Auth::user()->id;
		$person = Person::find($gen_user);

		$my_request_supplies = RequestSupplies::where('request_supplies.requested_by', $gen_user)
			->join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->leftjoin('purchase_order','request_supplies.id','=','purchase_order.request_supplies_id')
			->select('inventory_name.name', 'inventory.inv_unit', 'request_supplies.request_quantity', 'inventory.inv_brand', 'request_supplies.action_type','request_supplies.release_supplies_qty','purchase_order.id as purchase_order_id')
			->get();

		return view('teacher.track_request', compact('my_request_supplies'));
	}


	public function CheckedStatusRequest()
	{
		$gen_user = Auth::user()->id;
		
		$check_status_request = RequestSupplies::where('request_supplies.requested_by', $gen_user)
			->join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('request_supplies.action_type', 'inventory_name.name as item_name') 
			->whereIn('request_supplies.action_type', [2, 3, 4, 5]) 
			->get();
	
		return response()->json(['check_status_request' => $check_status_request]);
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
