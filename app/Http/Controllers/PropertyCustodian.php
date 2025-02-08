<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\InventoryName;
use App\RequestSupplies;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PropertyCustodian extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('pc.index');
	}
	

	public function inventory()
	{

		$inventory_name_list = InventoryName::all();
		return view('pc.inventory',compact('inventory_name_list'));
	}

	public function InventoryCreate()
	{
	
		$inv_name_id = \Request::get('inv_name_id');
		$inv_unit = \Request::get('inv_unit');
		$inv_brand = \Request::get('inv_brand');
		$inv_desc = \Request::get('inv_desc');
		$inv_amount = \Request::get('inv_amount');
		$inv_quantity = \Request::get('inv_quantity');
		$inv_total_amount = \Request::get('inv_total_amount');
		$inv_location = \Request::get('inv_location');


		$inventory = Inventory::create([
			'inv_name_id' => $inv_name_id,
			'inv_unit' => $inv_unit,
			'inv_brand' => $inv_brand,
			'inv_desc' => $inv_desc,
			'inv_quantity' => $inv_quantity,
			'inv_amount' => $inv_amount,
			'inv_total_amount' => $inv_total_amount,
			'inv_location' => $inv_location
		]);


		return response()->json(['success' => true, 'inventory' => $inventory]);
	}

	public function GetInventory()
	{
		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('inventory_name.name', 'inventory_name.description', 'inventory.id as inventory_id', 'inventory.inv_unit', 'inventory.inv_quantity', 'inventory.inv_brand', 'inventory.inv_name_id','inventory.inv_desc','inventory.inv_amount','inventory.inv_total_amount','inventory.inv_location')
			->get();
	
		$datatable = $inventory_list->map(function ($inventory) {
			return [
				'name' => $inventory->name,
				'inv_brand' => $inventory->inv_brand,
				'inv_desc' => $inventory->inv_desc,
				'inv_amount' => $inventory->inv_amount,
				'inv_quantity' => $inventory->inv_quantity,
				'inv_unit' => $inventory->inv_unit,
				'inv_total_amount' => $inventory->inv_total_amount,
				'inv_location' => $inventory->inv_location,
				'action' => '
					<a type="button" class="btn btn-primary btn-sm text-white editBtn" 
						  data-bs-toggle="modal" data-bs-target="#updateItemModal"
						data-inventory_list="' . base64_encode(json_encode($inventory)) . '" 
						style="margin: 4px;">
						<span class="fa fa-edit"></span> Edit
					</a>
					<a type="button" class="btn btn-danger btn-sm text-white deleteBtn" 
						data-inventory_id="' . $inventory->inventory_id . '" 
						style="margin: 4px;">
						<span class="fa fa-trash"></span> Delete
					</a>'
			];
		});
	
		return response()->json($datatable);
	}

	public function CheckedStatusRequestData()
	{
		$check_status_request_data = RequestSupplies::where('request_supplies.action_type', 4)
			->join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('request_supplies.action_type', 'inventory_name.name as item_name') 
			->get();

		return response()->json([
			'check_status_request' => $check_status_request_data,
		]);
	}


	public function InventoryUpdate(Request $request)
	{
		
		// dd(\Request::all());
		try {
		
			$update = Inventory::find($request->id);

			$update->inv_name_id = $request->inv_name_id;
			$update->inv_unit = $request->inv_unit;
			$update->inv_quantity = $request->inv_quantity;
			$update->inv_brand = $request->inv_brand;
			$update->inv_desc = $request->inv_desc;
			$update->inv_amount = $request->inv_amount;
			$update->inv_total_amount = $request->inv_total_amount;
			$update->inv_location = $request->inv_location;

			$update->save(); 

			return response()->json(['status' => 'success', 'message' => 'Inventory updated successfully.']);

		} catch (\Exception $e) {
			return response()->json([
				'status' => 'failed',
				'message' => 'An error has occurred. Please contact the developer for assistance.',
				'error' => $e->getMessage(),
				'line' => $e->getLine()
			]);
		}
	}

	public function GetReleaseData()
	{
		return view('pc.request_data');
	}

	public function GetForReleaseData()
	{
		$gen_user = Auth::user()->id;

		$user = User::find($gen_user);

		$get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->join('users as request_user', 'request_supplies.requested_by', '=', 'request_user.id')
			->join('person as request_person', 'request_user.person_id', '=', 'request_person.id')
			->leftJoin('users as approve_user', 'request_supplies.approved_by', '=', 'approve_user.id')
			->leftJoin('person as approve_person', 'approve_user.person_id', '=', 'approve_person.id')
			->whereIn('request_supplies.action_type', [4,5,6])
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
			$statusText = ($request->action_type == 4) ? 'For Release' :
			(($request->action_type == 5) ? 'For Pick Up' :
			(($request->action_type == 6) ? 'Done Release' : ''));

			$statusBadgeClass = ($request->action_type == 4) ? 'badge-soft-warning' :
							(($request->action_type == 5) ? 'badge-soft-success' :
							(($request->action_type == 6) ? 'badge-soft-primary' : 'badge-soft-secondary')) ;

			$approveButton = ($request->action_type == 5) ? 
			'<button type="button" class="btn btn-success btn-sm text-white forReleaseBtn" data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
				<span class="fa fa-check"></span> For Pick Up
			</button>' :
			(($request->action_type == 6) ?
			'<button type="button" class="btn btn-primary btn-sm text-white" disabled  style="margin: 4px;>
				<span class="fa fa-check"></span> Done Release
			</button>' :
			'<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
				data-request_supplies_id="' . $request->id . '" 
				style="margin: 4px;">
				<span class="fa fa-check"></span> Approve
			</a>');


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

        $get_request_supplies->action_type = 5;
        $get_request_supplies->save();

        $request_quantity = $get_request_supplies->request_quantity;
        $request_inventory_id = $get_request_supplies->inventory_id;

        $inventory = Inventory::where('id', $request_inventory_id)->first();
		// dd($inventory);
        if (!$inventory) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Inventory not found.'
            ]);
        }

      
		$inv_quantity = $inventory->inv_quantity;

		if ($request_quantity > $inv_quantity) {
			
			$inventory->inv_quantity = 0; 
			$inventory->save();

			return response()->json([
				'status' => 'success',
				'message' => 'Request Supplies Approved successfully, but inventory is not enough. Stock has been empty.',
				'new_inventory_quantity' => 0 
			]);
		}
	
        $new_inv_quantity = $inv_quantity - $request_quantity;
        $inventory->inv_quantity = $new_inv_quantity;
        $inventory->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Request Supplies Approved successfully.',
            'new_inventory_quantity' => $new_inv_quantity 
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

public function ForReleaseRequest(Request $request)
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
			$get_request_supplies->action_type = 6;
			$get_request_supplies->release_date = Carbon::now(); 
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
	public function destroy(Request $request)
{
    try {
        $delete = Inventory::find($request->inventory_id);

        if (!$delete) {
            return response()->json(['status' => 'failed', 'message' => 'Inventory item not found.']);
        }

        $delete->delete();

        return response()->json(['status' => 'success', 'message' => 'Inventory deleted successfully.']);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'failed',
            'message' => 'An error occurred while deleting the inventory.',
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
    }
}


}
