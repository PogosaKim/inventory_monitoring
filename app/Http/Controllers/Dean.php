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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use HTTP_Request2;
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

	public function GetNewData()
	{
		return view('dean.new_request_data');
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
		$finance_head = Person::where('person.id',24)->first();
		$pc = Person::where('person.id',1)->first();

		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
		->select('inventory.id as inventory_id', 'inventory_name.name', 'inventory_name.description', 'inventory.inv_unit', 'inventory.inv_quantity','inv_amount')
		->where('inventory.inv_quantity', '!=', 0)
		->get();
		// dd($inventory_list);
	
	
	
		return view('dean.request',compact('role','dean','person','inventory_list','finance_head','pc'));
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
		// ->where('request_supplies.user_role_id',$user->user_role_id)
		->whereIn('request_supplies.action_type',[2,3,4,5,6])
		->select('request_supplies.request_supplies_code','person.first_name','person.last_name','person.middle_name','inventory_name.name','request_supplies.request_quantity','request_supplies.date','request_supplies.action_type',DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
		DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
		DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids"))
		->groupBy('request_supplies.request_supplies_code')
		->orderBy('request_supplies.updated_at','asc')
		->get();
		// dd($get_request_supplies);


		$datatable = $get_request_supplies->map(function ($request) {
			switch ($request->action_type) {
				case 1:
					$statusText = 'Pending';
					$statusBadgeClass = 'badge-soft-warning';
					break;
				case 2:
					$statusText = 'Approved';
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
			$approveButton = in_array($request->action_type, [2, 3, 4, 5,6])
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
				'name' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
					style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
					class="viewDetail">'.strtoupper(trim($request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name)).'</a>',
				'item' =>$request->item_names,
				'quantity' =>$request->request_quantities,
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
		$get_request_supplies = RequestSupplies::join('inventory','request_supplies.inventory_id','=','inventory.id')
		->join('inventory_name','inventory.inv_name_id','=','inventory_name.id')
		->join('users','request_supplies.requested_by','=','users.id')
		->join('person','users.person_id','=','person.id')
		->where('request_supplies.school_department_id',$user->school_department_id)
		// ->where('request_supplies.user_role_id',$user->user_role_id)
		->where('request_supplies.action_type',1)
		->selectRaw("
			GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids,
			request_supplies.request_supplies_code,
			person.first_name, 
			person.middle_name, 
			person.last_name, 
			GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names,
			GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities,
			request_supplies.request_quantity,
			request_supplies.date,
			request_supplies.action_type
		")
		->groupBy('request_supplies.request_supplies_code')
		->orderBy('request_supplies.updated_at','asc')
		->get();
		// dd($get_request_supplies);


		$datatable = $get_request_supplies->map(function ($request) {
			switch ($request->action_type) {
				case 1:
					$statusText = 'Pending';
					$statusBadgeClass = 'badge-soft-warning';
					break;
				case 2:
					$statusText = 'Approved';
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
			$approveButton = in_array($request->action_type, [2, 3, 4, 5,6])
            ? '<button type="button" class="btn btn-success btn-sm text-white" disabled 
                style="margin: 4px;">
                <span class="fa fa-check"></span> Approved
              </button>'
            : '<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
                data-request_supplies_id="['. $request->request_supplies_ids .']"  
                style="margin: 4px;">
                <span class="fa fa-check"></span> Approve
              </a>';
			return [
				'name' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
							style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
							class="viewDetail">'.strtoupper(trim($request->first_name . ' ' . ($request->middle ? $request->middle . ' ' : '') . $request->last_name)).'</a>',
				'item' =>$request->item_names,
				'quantity' =>$request->request_quantities,
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
		->join('school_department','request_supplies.school_department_id','=','school_department.id')
		->join('users','request_supplies.requested_by','=','users.id')
		->join('person','users.person_id','=','person.id')
		->where('request_supplies.request_supplies_code', $request_supplies_code)
		->select('inventory_name.name', 'request_supplies.request_quantity', 'request_supplies.inv_unit_price', 'request_supplies.inv_unit_total_price','request_supplies.date','school_department.name as department_name','person.last_name','person.first_name','person.middle_name','person.signature')
		->first();
		// dd($)
	
	
		return view('dean.my_request_form',compact('role','teacher','person','inventory_list','finance_head','pc','my_request_supplies','my_request_supplies_details'));
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
	
			$request_supplies_ids = is_array($request->request_supplies_ids) ? 
									$request->request_supplies_ids : 
									[$request->request_supplies_ids];
	
			// Begin transaction for consistency
			\DB::beginTransaction();
	
			// Update the request supplies
			$updated = RequestSupplies::whereIn('id', $request_supplies_ids)
				->update([
					'approved_by' => $user->id,
					'action_type' => 2
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
			// dd($approvedRequests);
	
		

			$requestedByUser = User::find($approvedRequests->first()->requested_by);
			
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
	
			Mail::send('emails.dean_approved',
				['inventoryDetails' => $inventoryDetails, 'requestCode' => $requestCode],
				function($message) use ($requestedByUser) {
					$message->to($requestedByUser->email)
							->subject('Inventory Request Approved - ' . date('Y-m-d'));
				}
			);

			$smsMessage = "Inventory Request Approved (Code: $requestCode)\n";
			foreach ($approvedRequests as $approvedRequest) {
				$itemName = $approvedRequest->name ?: 'Unknown Item';
				$smsMessage .= "- $itemName: {$approvedRequest->request_quantity} units, Total: " . number_format($approvedRequest->inv_unit_total_price, 2) . "\n";
			}
			$smsMessage .= "Thank you, Custodian Office";
	
			// Sanitize phone number to E.164 format for Philippine numbers
			$toPhoneNumber = $requestedByUser->phone_number;
			if (!preg_match('/^\+\d{10,15}$/', $toPhoneNumber)) {
				$toPhoneNumber = preg_replace('/[^0-9+]/', '', $toPhoneNumber);
				if (preg_match('/^09\d{9}$/', $toPhoneNumber)) {
					$toPhoneNumber = '+63' . substr($toPhoneNumber, 2);
				} elseif (preg_match('/^09\d{10}$/', $toPhoneNumber)) {
					$toPhoneNumber = '+63' . substr($toPhoneNumber, 2, 10);
					\Log::warning("Trimmed invalid 11-digit Philippine number: {$requestedByUser->phone_number} to $toPhoneNumber");
				} else {
					throw new \Exception("Unrecognized phone number format: $requestedByUser->phone_number");
				}
				if (!preg_match('/^\+63\d{10}$/', $toPhoneNumber)) {
					throw new \Exception("Invalid phone number format after sanitization: $requestedByUser->phone_number (converted to $toPhoneNumber)");
				}
			}
	
			// Send SMS using Infobip API
			$httpRequest = new HTTP_Request2();
			$infobipBaseUrl = rtrim(env('INFOBIP_BASE_URL'), '/');
			$fullUrl = $infobipBaseUrl . '/sms/2/text/advanced';
			if (!filter_var($fullUrl, FILTER_VALIDATE_URL)) {
				throw new \Exception("Invalid Infobip URL: $fullUrl");
			}
			$httpRequest->setUrl($fullUrl);
			$httpRequest->setMethod(HTTP_Request2::METHOD_POST);
			$httpRequest->setConfig(['follow_redirects' => true]);
			$httpRequest->setHeader([
				'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
				'Content-Type' => 'application/json',
				'Accept' => 'application/json'
			]);
	
			$smsPayload = [
				'messages' => [
					[
						'destinations' => [['to' => $toPhoneNumber]],
						'from' => env('INFOBIP_FROM'),
						'text' => $smsMessage
					]
				]
			];
			$httpRequest->setBody(json_encode($smsPayload));
	
			$response = $httpRequest->send();
			if ($response->getStatus() != 200) {
				throw new \Exception('Infobip SMS failed: ' . $response->getReasonPhrase() . ' - ' . $response->getBody());
			}
			\DB::commit();
	
			return response()->json([
				'status' => 'success',
				'message' => 'Request Supplies Approved successfully and notification sent.'
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
//     try {
//         $gen_user = Auth::id();
//         $user = User::find($gen_user);

//         if (!$user) {
//             return response()->json([
//                 'status' => 'failed',
//                 'message' => 'User not found.'
//             ]);
//         }

//         $updated_requests = RequestSupplies::where('school_department_id', $user->school_department_id)
//             ->where('user_role_id', $user->user_role_id)
//             ->where('action_type', 1) 
//             ->update([
//                 'approved_by' => $user->id,
//                 'action_type' => 2,
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
//             'message' => 'All pending request supplies approved successfully.'
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
                'approved_by' => $user->id,
                'action_type' => 2,
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





public function Createrequest()
{
	$user_role_id = \Request::get('user_role_id');
    $date = \Request::get('date');
    $school_department_id = \Request::get('school_department_id');
    $inventory_ids = \Request::get('inventory_id');
    $request_quantities = \Request::get('request_quantity');
	$inv_unit_prices = \Request::get('inv_unit_price');
	$inv_unit_total_prices = \Request::get('inv_unit_total_price');
    $request_supplies_code = Str::random(5);
	try {
		// foreach ($inventory_ids as $index => $inventoryId) {
		// 	RequestSupplies::create([
		// 		'inventory_id' => $inventoryId,  
		// 		'requested_by' => Auth::user()->id,
		// 		'user_role_id' => $user_role_id,
		// 		'school_department_id' => $school_department_id,
		// 		'date' => $date,
		// 		'request_quantity' => $request_quantities[$index],
		// 		'action_type' => 2
		// 	]);
		// }

		foreach ($inventory_ids as $index => $inventoryId) {
            RequestSupplies::create([
                'inventory_id' => $inventoryId,
                'requested_by' => Auth::user()->id,
                'user_role_id' => $user_role_id,
                'school_department_id' => $school_department_id,
                'date' => $date,
                'request_quantity' => $request_quantities[$index],
                'action_type' => 2,
                'inv_unit_price' => $inv_unit_prices[$index],  // Fix here
        		'inv_unit_total_price' => $inv_unit_total_prices[$index], // Fix here
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

		return view('dean.track_request', compact('my_request_supplies'));
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

		public function CheckedStatusRequestData()
	{
		$gen_user_department = Auth::user()->school_department_id;
		
		$check_status_request_data = RequestSupplies::where('request_supplies.school_department_id', $gen_user_department)
			->where('request_supplies.action_type', 1)
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
