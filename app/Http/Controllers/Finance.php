<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\RequestSupplies;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class Finance extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('finance.index');
	}

	public function GetData()
	{
		return view('finance.request_data');
	}

	public function GetRequest()
	{
		$gen_user = Auth::user()->id;

		$user = User::find($gen_user);

		$get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->join('users as request_user', 'request_supplies.requested_by', '=', 'request_user.id')
			->join('person as request_person', 'request_user.person_id', '=', 'request_person.id')
			->leftJoin('users as approve_user', 'request_supplies.approved_by', '=', 'approve_user.id')
			->leftJoin('person as approve_person', 'approve_user.person_id', '=', 'approve_person.id')
			->whereIn('request_supplies.action_type', [3, 4 , 5 , 6])
			->select(
				'request_supplies.id',
				'request_person.first_name as requested_first_name',
				'request_person.middle_name as requested_middle_name',
				'request_person.last_name as requested_last_name',
				'approve_person.first_name as approved_first_name',
				'approve_person.middle_name as approved_middle_name',
				'approve_person.last_name as approved_last_name',
				'inventory_name.name',
				'request_supplies.request_quantity',
				'request_supplies.date',
				'request_supplies.action_type'
			)
			->orderBy('request_supplies.updated_at','desc')
			->get();

		$datatable = $get_request_supplies->map(function ($request) {
			switch ($request->action_type) {
				case 2:
					$statusText = 'Approved By Dean';
					$statusBadgeClass = 'badge-soft-success';
					break;
				case 3:
					$statusText = 'Approved by President';
					$statusBadgeClass = 'badge-soft-info';
					break;
				case 4:
					$statusText = 'Approved by Finance';
					$statusBadgeClass = 'badge-soft-primary';
					break;
				case 5:
					$statusText = 'Approved by Pick Up';
					$statusBadgeClass = 'badge-soft-dark';
					break;
				case 6:
					$statusText = 'Done Release';
					$statusBadgeClass = 'badge-soft-success';
					break;
				default:
					$statusText = 'Unknown';
					$statusBadgeClass = 'badge-soft-secondary';
					break;
			}

			$approveButton = in_array($request->action_type, [4, 5,6])
				? '<button type="button" class="btn btn-success btn-sm text-white" disabled style="margin: 4px;">
					<span class="fa fa-check"></span> For Release
				</button>'
				: '<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
					data-request_supplies_id="' . $request->id . '" 
					style="margin: 4px;">
					<span class="fa fa-check"></span> Approve
				</a>';

			$requestedBy = strtoupper(trim(
				$request->requested_first_name . ' ' . 
				($request->requested_middle_name ? $request->requested_middle_name . ' ' : '') . 
				$request->requested_last_name
			));

			$approvedBy = strtoupper(trim(
				$request->approved_first_name . ' ' . 
				($request->approved_middle_name ? $request->approved_middle_name . ' ' : '') . 
				$request->approved_last_name
			));

			return [
				'requested_by' => $requestedBy,
				'item' => $request->name,
				'quantity' => $request->request_quantity,
				'date' => Carbon::parse($request->date)->format('F j, Y'),
				'status' => '<small class="badge fw-semi-bold rounded-pill status ' . $statusBadgeClass . '">' . $statusText . '</small>',
				'action' => $approveButton,
			];
		});

		return response()->json($datatable);
	}

	public function CheckedStatusRequestData()
	{
		$check_status_request_data = RequestSupplies::where('request_supplies.action_type', 3)
			->join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('request_supplies.action_type', 'inventory_name.name as item_name') 
			->get();

		return response()->json([
			'check_status_request' => $check_status_request_data,
		]);
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

        $requesting_user = User::find($get_request_supplies->requested_by);

        if (!$requesting_user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Requesting user not found.'
            ]);
        }

        $get_request_supplies->approved_by_finance = $user->id;
        $get_request_supplies->action_type = 4;

        if ($requesting_user->user_role_id == 1) {
            $get_request_supplies->is_purchase_order = 1;
        }

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
