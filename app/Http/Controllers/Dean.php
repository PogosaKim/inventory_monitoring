<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\RequestSupplies;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class Dean extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('dean.index');
	}

	public function GetData()
	{
		return view('dean.request_data');
	}
	

	public function GetRequest()
	{
		$gen_user = Auth::user()->id;
		
		$user = User::find($gen_user);
		// dd($user);
		$get_request_supplies = RequestSupplies::join('inventory','request_supplies.inventory_id','=','inventory.id')
		->join('inventory_name','inventory.inv_name_id','=','inventory_name.id')
		->join('users','request_supplies.requested_by','=','users.id')
		->join('person','users.person_id','=','person.id')
		->where('request_supplies.school_department_id',$user->school_department_id)
		->where('request_supplies.user_role_id',$user->user_role_id)
		->select('request_supplies.id','person.first_name','person.last_name','person.middle_name','inventory_name.name','request_supplies.request_quantity','request_supplies.date','request_supplies.action_type')
		->get();


		$datatable = $get_request_supplies->map(function ($request) {
			$statusText = $request->action_type == 1 ? 'Pending' : ($request->action_type == 2 ? 'Approved' : '');
			$statusBadgeClass = $request->action_type == 1 ? 'badge-soft-warning' : ($request->action_type == 2 ? 'badge-soft-success' : 'badge-soft-secondary');
			return [
				'name' => strtoupper(trim($request->first_name . ' ' . ($request->middle ? $request->middle . ' ' : '') . $request->last_name)), 
				'item' =>$request->name,
				'quantity' =>$request->request_quantity,
				'date' => Carbon::parse($request->date)->format('F j, Y'),
				'status' => '<small class="badge fw-semi-bold rounded-pill status ' . $statusBadgeClass . '">' . $statusText . '</small>',
				'action' =>'<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
						data-inventory_id="' . $request->id . '" 
						style="margin: 4px;">
						<span class="fa fa-check"></span> Approved
					</a>',
			];
		});
	
		return response()->json($datatable);


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
