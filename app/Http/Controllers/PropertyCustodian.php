<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\InventoryName;
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
		$inv_quantity = \Request::get('inv_quantity');
		$date_purchase = \Request::get('date_purchase');


		$inventory = Inventory::create([
			'inv_name_id' => $inv_name_id,
			'inv_unit' => $inv_unit,
			'inv_quantity' => $inv_quantity,
			'date_purchase' => $date_purchase,
		]);


		return response()->json(['success' => true, 'inventory' => $inventory]);
	}

	public function GetInventory()
	{
		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->select('inventory_name.name', 'inventory_name.description', 'inventory.id as inventory_id', 'inventory.inv_unit', 'inventory.inv_quantity', 'inventory.date_purchase', 'inventory.inv_name_id')
			->get();
	
		$datatable = $inventory_list->map(function ($inventory) {
			return [
				'name' => $inventory->name,
				'description' => $inventory->description,
				'inv_quantity' => $inventory->inv_quantity,
				'inv_unit' => $inventory->inv_unit,
				'date_purchase' => $inventory->date_purchase,
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

	public function InventoryUpdate(Request $request)
	{
		try {
		
			$update = Inventory::find($request->id);

			$update->inv_name_id = $request->inv_name_id;
			$update->inv_unit = $request->inv_unit;
			$update->inv_quantity = $request->inv_quantity;
			$update->date_purchase = $request->date_purchase;
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
