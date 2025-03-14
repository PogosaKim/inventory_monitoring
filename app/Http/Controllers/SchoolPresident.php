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
use Illuminate\Support\Facades\Mail;

class SchoolPresident extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('school_president.index');
	}

	public function GetData()
	{
		return view('school_president.request_data');
	}

	public function GetNewData()
	{
		return view('school_president.new_request_data');
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
			->whereIn('request_supplies.action_type',  [3, 4 , 5 , 6])
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
			$approveButton = in_array($request->action_type, [3, 4, 5,6])
				? '<button type="button" class="btn btn-success btn-sm text-white" disabled style="margin: 4px;">
					<span class="fa fa-check"></span> Approved
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
			->where('request_supplies.action_type', 2)
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
			$approveButton = in_array($request->action_type, [3, 4, 5,6])
				? '<button type="button" class="btn btn-success btn-sm text-white" disabled style="margin: 4px;">
					<span class="fa fa-check"></span> Approved
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
    ->join('person as requested_person', 'requested_user.person_id', '=', 'requested_person.id')
    ->join('person as approved_person', 'approved_user.person_id', '=', 'approved_person.id')
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
        'approved_person.signature as approved_signature'
    )
    ->first();

		// dd($)
	
	
		return view('school_president.my_request_form',compact('role','teacher','person','inventory_list','finance_head','pc','my_request_supplies','my_request_supplies_details'));
	}
	


	public function GetApprovedRequest(Request $request)
	{
		// try {
		// 	$gen_user = Auth::id();
		// 	$user = User::find($gen_user);
	
		// 	if (!$user) {
		// 		return response()->json([
		// 			'status' => 'failed',
		// 			'message' => 'User not found.'
		// 		]);
		// 	}
	
		// 	$get_request_supplies = RequestSupplies::find($request->request_supplies_id);
	
		// 	if (!$get_request_supplies) {
		// 		return response()->json([
		// 			'status' => 'failed',
		// 			'message' => 'Request Supplies not found.'
		// 		]);
		// 	}
	
		// 	$get_request_supplies->approved_by_president = $user->id;
		// 	$get_request_supplies->action_type = 3;
		// 	$get_request_supplies->save();
	
		// 	return response()->json([
		// 		'status' => 'success',
		// 		'message' => 'Request Supplies Approved successfully.'
		// 	]);
	
		// } catch (\Exception $e) {
		// 	return response()->json([
		// 		'status' => 'failed',
		// 		'message' => 'An error occurred while approving the request.',
		// 		'error' => $e->getMessage(),
		// 		'line' => $e->getLine()
		// 	]);
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
	
			// Begin transaction for consistency
			\DB::beginTransaction();
	
			// Update the request supplies with president approval
			$updated = RequestSupplies::whereIn('id', $request_supplies_ids)
				->update([
					'approved_by_president' => $user->id,
					'action_type' => 3
				]);
	
			if (!$updated) {
				\DB::rollBack();
				return response()->json([
					'status' => 'failed',
					'message' => 'No records were updated. Please check the request IDs.'
				]);
			}
	
			// Get the approved requests with their details
			$approvedRequests = RequestSupplies::whereIn('request_supplies.id', $request_supplies_ids)
			->join('inventory', 'request_supplies.inventory_id','=','inventory.id')
			->join('inventory_name','inventory.inv_name_id','=','inventory_name.id')
			->select(
                'request_supplies.*', // Get all request_supplies columns
                'inventory_name.name' // Explicitly select the name column
            )
			->get();
	
			if ($approvedRequests->isEmpty()) {
				\DB::rollBack();
				return response()->json([
					'status' => 'failed',
					'message' => 'No approved requests found.'
				]);
			}
	
			// Get the user who made the request
			$requestedByUser = User::find($approvedRequests->first()->requested_by);
			
			if (!$requestedByUser) {
				\DB::rollBack();
				throw new \Exception('Requesting user not found.');
			}
	
			// Prepare inventory details for email
			$inventoryDetails = [];
			foreach ($approvedRequests as $approvedRequest) {
				$inventoryDetails[] = [
					'name' => $approvedRequest->name ? : 'Unknown Item', 
					'quantity' => $approvedRequest->request_quantity,
					'unit_price' => $approvedRequest->inv_unit_price,
					'total_price' => $approvedRequest->inv_unit_total_price
				];
			}
	
			
			$requestCode = $approvedRequests->first()->request_supplies_code;
	
		
			Mail::send('emails.president_approval_notification',
				['inventoryDetails' => $inventoryDetails, 'requestCode' => $requestCode],
				function($message) use ($requestedByUser) {
					$message->to($requestedByUser->email)
							->subject('Inventory Request Approved by President - ' . date('Y-m-d'));
				}
			);
	
			\DB::commit();
	
			return response()->json([
				'status' => 'success',
				'message' => 'Request Supplies Approved by President successfully and notification sent.'
			]);
	
		} catch (\Exception $e) {
			\DB::rollBack();
			return response()->json([
				'status' => 'failed',
				'message' => 'An error occurred while approving the request or sending notification.',
				'error' => $e->getMessage(),
				'line' => $e->getLine()
			]);
		}
		
	}

	// public function GetApprovedAllRequest(Request $request)
	// {
	// 	try {
	// 		$gen_user = Auth::id();
	// 		$user = User::find($gen_user);
	
	// 		if (!$user) {
	// 			return response()->json([
	// 				'status' => 'failed',
	// 				'message' => 'User not found.'
	// 			]);
	// 		}
	
	// 		$request_supplies_ids = $request->request_supplies_ids ? : [];
	
	// 		if (empty($request_supplies_ids)) {
	// 			return response()->json([
	// 				'status' => 'failed',
	// 				'message' => 'No request supplies IDs provided.'
	// 			]);
	// 		}
	
	// 		$updated_requests = RequestSupplies::whereIn('id', $request_supplies_ids)
	// 			->where('action_type', 2) 
	// 			->update([
	// 				'approved_by' => $user->id,
	// 				'action_type' => 3, 
	// 				'updated_at' => date('Y-m-d H:i:s') 
	// 			]);
	
	// 		if ($updated_requests == 0) {
	// 			return response()->json([
	// 				'status' => 'failed',
	// 				'message' => 'No pending requests found to approve.'
	// 			]);
	// 		}
	
	// 		return response()->json([
	// 			'status' => 'success',
	// 			'message' => 'All pending request supplies approved successfully.'
	// 		]);
	
	// 	} catch (\Exception $e) {
	// 		return response()->json([
	// 			'status' => 'failed',
	// 			'message' => 'An error occurred while approving the requests.',
	// 			'error' => $e->getMessage(),
	// 			'line' => $e->getLine()
	// 		]);
	// 	}
	// }

	public function GetApprovedAllRequest(Request $request)
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


    //     $request_supplies_ids = $request->input('request_supplies_ids', []);

    //     if (!is_array($request_supplies_ids)) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'Invalid request data.'
    //         ]);
    //     }


    //     $flat_ids = collect($request_supplies_ids)->flatten()->unique()->map(function ($id) {
    //         return intval($id); 
    //     })->toArray();

    //     if (empty($flat_ids)) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'No valid request supplies IDs found.'
    //         ]);
    //     }

      
    //     $updated_requests = RequestSupplies::whereIn('id', $flat_ids)
    //         ->update([
    //             'approved_by_president' => $user->id,
    //             'action_type' => 3,
    //             'updated_at' => date('Y-m-d H:i:s') 
    //         ]);

    //     if ($updated_requests == 0) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'No pending requests found to approve.'
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'All selected request supplies approved successfully.'
    //     ]);

    // } catch (\Exception $e) {
    //     return response()->json([
    //         'status' => 'failed',
    //         'message' => 'An error occurred while approving the requests.',
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

        // Begin transaction for consistency
        \DB::beginTransaction();

        // Update all selected requests with president approval
        $updated_requests = RequestSupplies::whereIn('id', $flat_ids)
            ->update([
                'approved_by_president' => $user->id,
                'action_type' => 3,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if ($updated_requests == 0) {
            \DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => 'No pending requests found to approve.'
            ]);
        }

        // Get all approved requests with details
		$approvedRequests = RequestSupplies::whereIn('request_supplies.id', $request_supplies_ids)
		->join('inventory', 'request_supplies.inventory_id','=','inventory.id')
		->join('inventory_name','inventory.inv_name_id','=','inventory_name.id')
		->select(
			'request_supplies.*', // Get all request_supplies columns
			'inventory_name.name' // Explicitly select the name column
		)
		->get();

        if ($approvedRequests->isEmpty()) {
            \DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => 'No approved requests found.'
            ]);
        }

        // Group requests by requested_by to send one email per user
        $requestsByUser = $approvedRequests->groupBy('requested_by');

        foreach ($requestsByUser as $requested_by => $userRequests) {
            $requesting_user = User::find($requested_by);

            if (!$requesting_user) {
                \DB::rollBack();
                throw new \Exception("Requesting user ID $requested_by not found.");
            }

            // Prepare inventory details for this user's requests
            $inventoryDetails = [];
            foreach ($userRequests as $approvedRequest) {
                $inventoryDetails[] = [
                    'name' => $approvedRequest->name ? : 'Unknown Item', 
                    'quantity' => $approvedRequest->request_quantity,
                    'unit_price' => $approvedRequest->inv_unit_price,
                    'total_price' => $approvedRequest->inv_unit_total_price
                ];
            }

            // Use the first request's code from the Collection
            $requestCode = $userRequests[0]->request_supplies_code; // Access first item as array

            // Send email to the requesting user
            Mail::send('emails.president_approval_notification',
                ['inventoryDetails' => $inventoryDetails, 'requestCode' => $requestCode],
                function($message) use ($requesting_user) {
                    $message->to($requesting_user->email)
                            ->subject('Inventory Requests Approved by President - ' . date('Y-m-d'));
                }
            );
        }

        \DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'All selected request supplies approved successfully and notifications sent.'
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        return response()->json([
            'status' => 'failed',
            'message' => 'An error occurred while approving the requests or sending notifications.',
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
    }


}

	

	public function CheckedStatusRequestData()
	{
		$check_status_request_data = RequestSupplies::where('request_supplies.action_type', 2)
			->join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('request_supplies.action_type', 'inventory_name.name as item_name') 
			->get();

		return response()->json([
			'check_status_request' => $check_status_request_data,
		]);
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
