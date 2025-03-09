<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Person;
use App\RequestSupplies;
use App\Roles;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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

	public function GetNewData()
	{
		return view('finance.new_request_data');
	}
	
	public function GetPurchaseOrderData()
	{
		return view('finance.purchase_data');
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
			->whereIn('request_supplies.action_type', [4 , 5 , 6])
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
				'request_supplies.action_type',
				'request_supplies.request_supplies_code',
				DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
				DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
				DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids")
			)
			->groupBy('request_supplies.request_supplies_code')
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
					$statusText = 'Done Pick Up';
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
				'requested_by' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
				style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
				class="viewDetail">'.strtoupper(trim($request->requested_first_name . ' ' . ($request->requested_middle_name ? $request->requested_middle_name . ' ' : '') . $request->requested_last_name)).'</a>',
				'item' => $request->item_names,
				'quantity' => $request->request_quantities,
				'date' => Carbon::parse($request->date)->format('F j, Y'),
				'status' => '<small class="badge fw-semi-bold rounded-pill status ' . $statusBadgeClass . '">' . $statusText . '</small>',
				'action' => $approveButton,
			];
		});

		return response()->json($datatable);
	}

	public function GetNewRequest()
	{
		$gen_user = Auth::user()->id;

		$user = User::find($gen_user);

		$get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->join('users as request_user', 'request_supplies.requested_by', '=', 'request_user.id')
			->join('person as request_person', 'request_user.person_id', '=', 'request_person.id')
			->leftJoin('users as approve_user', 'request_supplies.approved_by', '=', 'approve_user.id')
			->leftJoin('person as approve_person', 'approve_user.person_id', '=', 'approve_person.id')
			->where('request_supplies.action_type', 3)
			->where(function($query) {
				$query->where('request_supplies.is_request_purchase_order', 0)
					  ->orWhereNull('request_supplies.is_request_purchase_order');
			})
			->select(
				'request_person.first_name as requested_first_name',
				'request_person.middle_name as requested_middle_name',
				'request_person.last_name as requested_last_name',
				'approve_person.first_name as approved_first_name',
				'approve_person.middle_name as approved_middle_name',
				'approve_person.last_name as approved_last_name',
				'inventory_name.name',
				'request_supplies.request_quantity',
				'request_supplies.date',
				'request_supplies.action_type',
				'request_supplies.request_supplies_code',
				DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
				DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
				DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids")
			)
			->groupBy('request_supplies.request_supplies_code')
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
					data-request_supplies_id="['. $request->request_supplies_ids .']"  
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
				'requested_by' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
				style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
				class="viewDetail">'.strtoupper(trim($request->requested_first_name . ' ' . ($request->requested_middle_name ? $request->requested_middle_name . ' ' : '') . $request->requested_last_name)).'</a>',
				'item' => $request->item_names,
				'quantity' => $request->request_quantities,
				'date' => Carbon::parse($request->date)->format('F j, Y'),
				'status' => '<small class="badge fw-semi-bold rounded-pill status ' . $statusBadgeClass . '">' . $statusText . '</small>',
				'action' => $approveButton,
			];
		});

		return response()->json($datatable);
	}

	public function my_request_data_form(){
		$request_supplies_id = \Request::get('request_supplies_id');
		$request_supplies_code = \Request::get('request_supplies_code');
	
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

	$my_request_supplies_details = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
    ->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
    ->join('school_department', 'request_supplies.school_department_id', '=', 'school_department.id')
    ->join('users as requested_user', 'request_supplies.requested_by', '=', 'requested_user.id')
    ->join('users as approved_user', 'request_supplies.approved_by', '=', 'approved_user.id')
	->join('users as approved_by_finance_user', 'request_supplies.approved_by_finance', '=', 'approved_by_finance_user.id') 
    ->join('person as requested_person', 'requested_user.person_id', '=', 'requested_person.id')
    ->join('person as approved_person', 'approved_user.person_id', '=', 'approved_person.id')
	->join('person as approved_by_finance_person', 'approved_by_finance_user.person_id', '=', 'approved_by_finance_person.id')
    ->where('request_supplies.request_supplies_code', $request_supplies_code)
    ->select(
        'inventory_name.name',
        'request_supplies.request_quantity',
        'request_supplies.inv_unit_price',
        'request_supplies.inv_unit_total_price',
        'request_supplies.date',
        'school_department.name as department_name',
        'requested_person.last_name as requested_last_name',
        'requested_person.first_name as requested_first_name',
        'requested_person.middle_name as requested_middle_name',
        'requested_person.signature as requested_signature',
        'approved_person.last_name as approved_last_name',
        'approved_person.first_name as approved_first_name',
        'approved_person.middle_name as approved_middle_name',
        'approved_person.signature as approved_signature',
		'approved_by_finance_person.last_name as approved_by_finance_last_name',
        'approved_by_finance_person.first_name as approved_by_finance_first_name',
        'approved_by_finance_person.middle_name as approved_by_finance_middle_name',
        'approved_by_finance_person.signature as approved_by_finance_signature'
    )
    ->first();

		// dd($my_request_supplies_details);
	
	
		return view('finance.my_request_form',compact('role','teacher','person','inventory_list','finance_head','pc','my_request_supplies','my_request_supplies_details'));
	}


	public function GetNewPurchaseRequest(){
		$gen_user = Auth::user()->id;

		$user = User::find($gen_user);

		$get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->join('users as request_user', 'request_supplies.requested_by', '=', 'request_user.id')
			->join('person as request_person', 'request_user.person_id', '=', 'request_person.id')
			->leftJoin('users as approve_user', 'request_supplies.approved_by', '=', 'approve_user.id')
			->leftJoin('person as approve_person', 'approve_user.person_id', '=', 'approve_person.id')
			->where('request_supplies.is_request_purchase_order', 1)
			->select(
				// 'request_supplies.id',
				'request_person.first_name as requested_first_name',
				'request_person.middle_name as requested_middle_name',
				'request_person.last_name as requested_last_name',
				'approve_person.first_name as approved_first_name',
				'approve_person.middle_name as approved_middle_name',
				'approve_person.last_name as approved_last_name',
				'inventory_name.name',
				'request_supplies.request_quantity',
				'request_supplies.date',
				'request_supplies.action_type',
				'request_supplies.is_request_purchase_order',
				DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
				DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
				DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids")
			)
			->groupBy('request_supplies.request_supplies_code')
			->orderBy('request_supplies.updated_at','desc')
			->get();

		$datatable = $get_request_supplies->map(function ($request) {
				if ($request->is_request_purchase_order == 1) {
					$statusText = 'Purchase Order';
					$statusBadgeClass = 'badge-soft-success';
				}

			$approveButton = '<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
						data-request_supplies_id="['. $request->request_supplies_ids .']"  
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
				'requested_by' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
				style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
				class="viewDetail">'.strtoupper(trim($request->requested_first_name . ' ' . ($request->requested_middle_name ? $request->requested_middle_name . ' ' : '') . $request->requested_last_name)).'</a>',
				'item' => $request->item_names,
				'quantity' => $request->request_quantities,
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
    // try {
    //     $gen_user = Auth::id();
    //     $user = User::find($gen_user);

    //     if (!$user) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'User not found.'
    //         ]);
    //     }

    //     $get_request_supplies = RequestSupplies::find($request->request_supplies_id);

    //     if (!$get_request_supplies) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'Request Supplies not found.'
    //         ]);
    //     }

    //     $requesting_user = User::find($get_request_supplies->requested_by);

    //     if (!$requesting_user) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'Requesting user not found.'
    //         ]);
    //     }

    //     $get_request_supplies->approved_by_finance = $user->id;
    //     $get_request_supplies->action_type = 4;

    //     if ($requesting_user->user_role_id == 1) {
    //         $get_request_supplies->is_purchase_order = 1;
	// 		$get_request_supplies->is_request_purchase_order = 2;
    //     }

    //     $get_request_supplies->save();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Request Supplies Approved successfully.'
    //     ]);

    // } catch (\Exception $e) {
    //     return response()->json([
    //         'status' => 'failed',
    //         'message' => 'An error occurred while approving the request.',
    //         'error' => $e->getMessage(),
    //         'line' => $e->getLine()
    //     ]);
    // }

	try {
		$gen_user = Auth::id();
		$user = User::find($gen_user);
	
		if (!$user) {
			return response()->json([
				'status' => 'failed',
				'message' => 'User not found.'
			]);
		}
	
	
		$request_supplies_ids = is_array($request->request_supplies_ids) ? 
								$request->request_supplies_ids : 
								[$request->request_supplies_ids];

		$get_request_supplies = RequestSupplies::whereIn('id', $request_supplies_ids)->first();
	
		if (!$get_request_supplies) {
			return response()->json([
				'status' => 'failed',
				'message' => 'Requested supplies record not found.'
			]);
		}
	
	
		$requesting_user = User::find($get_request_supplies->requested_by);
		if (!$requesting_user) {
			return response()->json([
				'status' => 'failed',
				'message' => 'Requesting user not found.'
			]);
		}
	

		$updated = RequestSupplies::whereIn('id', $request_supplies_ids)
			->update([
				'approved_by_finance' => $user->id,
				'action_type' => 4
			]);
	
		if ($requesting_user->user_role_id == 1) {
			$get_request_supplies->update([
				'is_purchase_order' => 1,
				'is_request_purchase_order' => 2
			]);
		}
	
		if ($updated) {
			return response()->json([
				'status' => 'success',
				'message' => 'Request Supplies Approved successfully.'
			]);
		} else {
			return response()->json([
				'status' => 'failed',
				'message' => 'No records were updated. Please check the request IDs.'
			]);
		}
	} catch (\Exception $e) {
		return response()->json([
			'status' => 'failed',
			'message' => 'An error occurred while approving the request.',
			'error' => $e->getMessage(),
			'line' => $e->getLine()
		]);
	}
	
	
	
}

// public function GetApprovedAllRequest(Request $request)
// 	{
// 		try {
// 			$gen_user = Auth::id();
// 			$user = User::find($gen_user);
	
// 			if (!$user) {
// 				return response()->json([
// 					'status' => 'failed',
// 					'message' => 'User not found.'
// 				]);
// 			}
	
// 			$request_supplies_ids = $request->request_supplies_ids ? : [];
	
// 			if (empty($request_supplies_ids)) {
// 				return response()->json([
// 					'status' => 'failed',
// 					'message' => 'No request supplies IDs provided.'
// 				]);
// 			}
	
// 			$updated_requests = RequestSupplies::whereIn('id', $request_supplies_ids)
// 				->where('action_type', 3) 
// 				->update([
// 					'approved_by' => $user->id,
// 					'action_type' => 4, 
// 					'updated_at' => date('Y-m-d H:i:s') 
// 				]);
	
// 			if ($updated_requests == 0) {
// 				return response()->json([
// 					'status' => 'failed',
// 					'message' => 'No pending requests found to approve.'
// 				]);
// 			}
	
// 			return response()->json([
// 				'status' => 'success',
// 				'message' => 'All pending request supplies approved successfully.'
// 			]);
	
// 		} catch (\Exception $e) {
// 			return response()->json([
// 				'status' => 'failed',
// 				'message' => 'An error occurred while approving the requests.',
// 				'error' => $e->getMessage(),
// 				'line' => $e->getLine()
// 			]);
// 		}
// 	}

// public function GetApprovedAllRequest(Request $request)
// {
//     try {
//         $gen_user = Auth::id();
//         $user = User::find($gen_user);

//         if (!$user) {
//             return response()->json([
//                 'status' => 'failed',
//                 'message' => 'User not found.'
//             ]);
//         }


//         $request_supplies_ids = $request->input('request_supplies_ids', []);

//         if (!is_array($request_supplies_ids)) {
//             return response()->json([
//                 'status' => 'failed',
//                 'message' => 'Invalid request data.'
//             ]);
//         }


//         $flat_ids = collect($request_supplies_ids)->flatten()->unique()->map(function ($id) {
//             return intval($id); 
//         })->toArray();

//         if (empty($flat_ids)) {
//             return response()->json([
//                 'status' => 'failed',
//                 'message' => 'No valid request supplies IDs found.'
//             ]);
//         }

      
//         $updated_requests = RequestSupplies::whereIn('id', $flat_ids)
//             ->update([
//                 'approved_by_president' => $user->id,
//                 'action_type' => 3,
//                 'updated_at' => date('Y-m-d H:i:s') 
//             ]);

//         if ($updated_requests == 0) {
//             return response()->json([
//                 'status' => 'failed',
//                 'message' => 'No pending requests found to approve.'
//             ]);
//         }

//         return response()->json([
//             'status' => 'success',
//             'message' => 'All selected request supplies approved successfully.'
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => 'failed',
//             'message' => 'An error occurred while approving the requests.',
//             'error' => $e->getMessage(),
//             'line' => $e->getLine()
//         ]);
//     }
// }


public function GetApprovedAllRequest(Request $request)
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


        $request_supplies_ids = $request->input('request_supplies_ids', []);

        if (!is_array($request_supplies_ids)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid request data.'
            ]);
        }


        $flat_ids = collect($request_supplies_ids)->flatten()->unique()->map(function ($id) {
            return intval($id); 
        })->toArray();

        if (empty($flat_ids)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No valid request supplies IDs found.'
            ]);
        }

      
        $updated_requests = RequestSupplies::whereIn('id', $flat_ids)
            ->update([
                'approved_by_finance' => $user->id,
                'action_type' => 4,
                'updated_at' => date('Y-m-d H:i:s') 
            ]);

        if ($updated_requests == 0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No pending requests found to approve.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'All selected request supplies approved successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'failed',
            'message' => 'An error occurred while approving the requests.',
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
