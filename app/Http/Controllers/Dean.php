<?php namespace App\Http\Controllers;

use App\Dean as AppDean;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\Person;
use App\RequestSupplies;
use App\Roles;
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

	public function request()
	{
		$gen_user = Auth::user()->person_id;

		$person = Person::find($gen_user);
		// dd($gen_user);
	
		$dean = AppDean::where('person_id', $gen_user)
						  ->join('school_department','dean.school_department_id','=','school_department.id')
						  ->select('dean.id as dean_id','school_department.name','school_department.suffix','school_department.id as school_department_id')
						  ->first();
		// dd($dean);
	
		if ($dean) {
			$role = Roles::where('id', 5)
             ->select('id', 'name')
             ->first();
		}
		// dd($role);



		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
		->select('inventory.id as inventory_id', 'inventory_name.name', 'inventory_name.description', 'inventory.inv_unit', 'inventory.inv_quantity')
		->where('inventory.inv_quantity', '!=', 0)
		->get();
		// dd($inventory_list);
	
	
	
		return view('dean.request',compact('role','dean','person','inventory_list'));
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
		->orderBy('request_supplies.date','asc')
		->get();


		$datatable = $get_request_supplies->map(function ($request) {
			$statusText = $request->action_type == 1 ? 'Pending' : ($request->action_type == 2 ? 'Approved' : '');
			$statusBadgeClass = $request->action_type == 1 ? 'badge-soft-warning' : ($request->action_type == 2 ? 'badge-soft-success' : 'badge-soft-secondary');
			$approveButton = ($request->action_type == 2)
            ? '<button type="button" class="btn btn-success btn-sm text-white" disabled 
                style="margin: 4px;">
                <span class="fa fa-check"></span> Approved
              </button>'
            : '<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
                data-request_supplies_id="' . $request->id . '" 
                style="margin: 4px;">
                <span class="fa fa-check"></span> Approve
              </a>';
			return [
				'name' => strtoupper(trim($request->first_name . ' ' . ($request->middle ? $request->middle . ' ' : '') . $request->last_name)), 
				'item' =>$request->name,
				'quantity' =>$request->request_quantity,
				'date' => Carbon::parse($request->date)->format('F j, Y'),
				'status' => '<small class="badge fw-semi-bold rounded-pill status ' . $statusBadgeClass . '">' . $statusText . '</small>',
				'action' => $approveButton,
			];
		});
	
		return response()->json($datatable);


	}


	public function GetApprovedRequest(Request $request)
{
    try {
        $gen_user = Auth::id();
        $user = User::find($gen_user);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User not found.'
            ]);
        }

        $get_request_supplies = RequestSupplies::find($request->request_supplies_id);

        if (!$get_request_supplies) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Request Supplies not found.'
            ]);
        }

        $get_request_supplies->approved_by = $user->id;
        $get_request_supplies->action_type = 2;
        $get_request_supplies->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Request Supplies Approved successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'failed',
            'message' => 'An error occurred while approving the request.',
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
    }
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
				'action_type' => 2
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
		// dd($gen_user);
		$my_request_supplies = RequestSupplies::where('request_supplies.requested_by', $gen_user)
			->join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('inventory_name.name', 'inventory.inv_unit', 'request_supplies.request_quantity', 'inventory.inv_brand', 'request_supplies.action_type')
			->get();
		// dd($my_request_supplies);

		return view('dean.track_request', compact('my_request_supplies'));
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
