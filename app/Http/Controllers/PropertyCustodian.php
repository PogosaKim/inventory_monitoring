<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\InventoryName;
use App\Person;
use App\PurchaseOrder;
use App\RequestSupplies;
use App\Roles;
use App\Teachers;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\File;
use Picqer\Barcode\BarcodeGenerator; 
use Zxing\QrReader;
use Illuminate\Support\Facades\DB;

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

	public function inventoryName()
	{
		return view('pc.inventory_name');
	}
	

	public function inventory()
	{

		$inventory_name_list = InventoryName::all();
		return view('pc.inventory',compact('inventory_name_list'));
	}

	public function PurchaseOrder()
	{
		$gen_user = Auth::user()->person_id;

		$person = Person::find($gen_user);
		// dd($person);

		$role = Roles::where('id', 3)
			->select('id', 'name')
			->first();
		
		$finance_head = Person::where('person.id',24)->first();
		$pc = Person::where('person.id',1)->first();
		



		$inventory_list = Inventory::join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
		->select('inventory.id as inventory_id', 'inventory_name.name', 'inventory_name.description', 'inventory.inv_unit', 'inventory.inv_quantity')
		->where('inventory.inv_quantity',0)
		->get();


		return view('pc.purchase_order',compact('role','teacher','person','inventory_list','finance_head','pc'));
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
					'action_type' => 3 ,
					'is_purchase_order' => 1,
					'is_request_purchase_order' => 1
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

	public function InventoryNameCreate()
	{
		$inventory_name = \Request::get('inventory_name');
		$inventory_desc = \Request::get('inventory_desc');

		$inventory = InventoryName::create([
			'name' => $inventory_name,
			'description' => $inventory_desc,
		]);


		return response()->json(['success' => true, 'inventory' => $inventory]);
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
					</a>
					'
					
			];
		});
	
		return response()->json($datatable);
	}

	public function Scanner()
	{
		return view('pc.scanner');
	}

	



	public function generateBarcode($id)
	{
		$inventory = Inventory::findOrFail($id);
		$generator = new BarcodeGeneratorPNG();
		$barcodeType = $generator::TYPE_CODE_128;
		$barcodeData = $generator->getBarcode($id, $barcodeType, 3, 100);
	
		// Define file name and path
		$fileName = "barcode_{$id}.png";
		$destinationPath = public_path("assets/site/images/barcodes/");
	
		// Ensure directory exists
		if (!File::exists($destinationPath)) {
			File::makeDirectory($destinationPath, 0755, true, true);
		}
	
		// Save barcode image
		file_put_contents($destinationPath . $fileName, $barcodeData);
	
		// Save filename to the database
		if (!$inventory->barcode) {
			$inventory->barcode = $fileName;
			$inventory->save();
		}
	
		return view('pc.barcode', [
			'inventory' => $inventory,
			'barcode' => asset("assets/site/images/barcodes/{$fileName}"), // Use URL for display
		]);
	}
	public function uploadBarcode(Request $request)
    {
       

        $image = $request->file('barcode_image');
        $imagePath = $image->getPathname();

        // Use QrReader to decode the barcode
        $qrcode = new QrReader($imagePath);
        $barcodeText = $qrcode->text();

        if (empty($barcodeText)) {
            return response()->json(['error' => 'Barcode not detected'], 400);
        }

        // Find the inventory item by the barcode text (assuming the barcode text is the inventory ID)
        $inventory = Inventory::find($barcodeText);

        if (!$inventory) {
            return response()->json(['error' => 'Inventory not found'], 404);
        }

        // Return the inventory details
        return response()->json([
            'inventory' => $inventory,
            'barcode_text' => $barcodeText,
        ]);
    }



// 	public function generateBarcode($id)
// {
//     $inventory = Inventory::findOrFail($id);
//     $generator = new BarcodeGeneratorPNG();

//     // Generate barcode with increased size for better readability
//     $barcodeData = $generator->getBarcode($id, $generator::TYPE_CODE_128, 3, 100);

//     // Define the barcode file name and storage path
//     $fileName = "barcode_{$id}.png";
//     $destinationPath = public_path("assets/site/images/barcodes/");

//     // Ensure directory exists
//     if (!File::exists($destinationPath)) {
//         File::makeDirectory($destinationPath, 0777, true, true);
//     }

//     // Full path for the barcode file
//     $fullPath = $destinationPath . $fileName;

//     // Delete old barcode if exists
//     if (File::exists($fullPath)) {
//         File::delete($fullPath);
//     }

//     // Save new barcode image
//     file_put_contents($fullPath, $barcodeData);

//     // Verify if barcode was successfully created
//     if (!File::exists($fullPath)) {
//         return response()->json(['error' => 'Failed to save barcode image'], 500);
//     }

//     // Update barcode path in the database
//     $inventory->barcode = "assets/site/images/barcodes/{$fileName}";
//     $inventory->save();

//     return view('pc.barcode', [
//         'inventory' => $inventory,
//         'barcodePath' => asset("assets/site/images/barcodes/{$fileName}"),
//     ]);
// }
	

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

	public function GetNewReleaseData()
	{
		return view('pc.new_request_data');
	}

	public function GetPurchaseRecords(){
		return view('pc.po_request_data');
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
			->leftjoin('purchase_order','request_supplies.id','=','purchase_order.request_supplies_id')
			->where('request_supplies.action_type',6)
			// ->where(function($query) {
			// 	$query->where('request_supplies.is_request_purchase_order', '!=', 2)
			// 		  ->orWhereNull('request_supplies.is_request_purchase_order');
			// })	
			->select(
				// 'request_supplies.id',
				'request_person.first_name as requested_first_name',
				'request_person.middle_name as requested_middle_name',
				'request_person.last_name as requested_last_name',
				'request_person.signature as requested_signature',
				'approve_person.first_name as approved_first_name',
				'approve_person.middle_name as approved_middle_name',
				'approve_person.last_name as approved_last_name',
				'approve_person.signature as approved_signature',
				'inventory_name.name',
				'request_supplies.request_quantity',
				'request_supplies.release_supplies_qty',
				'request_supplies.date',
				'request_supplies.action_type',
				'request_supplies.is_purchase_order',
				'purchase_order.status as po_status',
				'purchase_order.id as purchase_order_id',
				'request_supplies.request_supplies_code',
				DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
				DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
				DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids")
			)
			->groupBy('request_supplies.request_supplies_code')
			->orderBy('request_supplies.updated_at','desc')
			->get();

			$datatable = $get_request_supplies->map(function ($request) {
				$statusText = ($request->action_type == 4) ? 'For Release' :
					(($request->action_type == 5) ? 'For Pick Up' :
					(($request->action_type == 6) ? 'Done Release' : ''));
		
				$statusBadgeClass = ($request->action_type == 4) ? 'badge-soft-warning' :
					(($request->action_type == 5) ? 'badge-soft-success' :
					(($request->action_type == 6) ? 'badge-soft-primary' : 'badge-soft-secondary'));
		
				if ($request->action_type == 6) {
					// if ($request->po_status == 1) {  
					// 	$approveButton = '<button type="button" class="btn btn-warning btn-sm text-white processPoBtn" 
					// 						data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
					// 						<span class="fa fa-truck"></span> Process PO
					// 					</button>';
					// } else {
					// 	$approveButton = '<button type="button" class="btn btn-primary btn-sm text-white" disabled style="margin: 4px;">
					// 						<span class="fa fa-check"></span> Done Release
					// 					</button>';
					// }
					$approveButton = '<button type="button" class="btn btn-primary btn-sm text-white" disabled style="margin: 4px;">
											<span class="fa fa-check"></span> Done Release
										</button>';
				} elseif ($request->action_type == 5) {
					$approveButton = '<button type="button" class="btn btn-success btn-sm text-white forReleaseBtn" 
										data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
										<span class="fa fa-check"></span> For Pick Up
									</button>';
				} elseif ($request->is_purchase_order == 1){
					$approveButton = '<button type="button" class="btn btn-warning btn-sm text-white approvedPoBtn" 
											data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
											<span class="fa fa-box"></span> Approved PO
										</button>';
				}
				 else {
					$approveButton = '<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
										data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
										<span class="fa fa-check"></span> Approve
									</a>';
				}
		
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
				$signaturePath = asset($request->requested_signature);
       		    $signatureImage = $request->requested_signature ? '<img src="' . $signaturePath . '" width="150" height="75">' : '';
				$needed = $request->purchase_order_id ? ($request->request_quantity - $request->release_supplies_qty) : null;
		
				return [
					'requested_by' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
					style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
					class="viewDetail">'.strtoupper(trim($request->requested_first_name . ' ' . ($request->requested_middle_name ? $request->middle . ' ' : '') . $request->requested_last_name)).'</a>',
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

		$gen_user = Auth::user()->person_id;
		$pc_details = Person::find($gen_user);

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
	
	
		return view('pc.my_request_form',compact('role','teacher','person','inventory_list','finance_head','pc','my_request_supplies','my_request_supplies_details','pc_details'));
	}

	public function my_request_accept_data_form(){
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

		$gen_user = Auth::user()->person_id;
		$pc_details = Person::find($gen_user);

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
	
	
		return view('pc.my_request_accept_data_form',compact('role','teacher','person','inventory_list','finance_head','pc','my_request_supplies','my_request_supplies_details','pc_details'));
	}

	

	public function GetForNewReleaseData()
	{
		$gen_user = Auth::user()->id;

		$user = User::find($gen_user);

		$get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->join('users as request_user', 'request_supplies.requested_by', '=', 'request_user.id')
			->join('person as request_person', 'request_user.person_id', '=', 'request_person.id')
			->leftJoin('users as approve_user', 'request_supplies.approved_by', '=', 'approve_user.id')
			->leftJoin('person as approve_person', 'approve_user.person_id', '=', 'approve_person.id')
			->leftjoin('purchase_order','request_supplies.id','=','purchase_order.request_supplies_id')
			->whereIn('request_supplies.action_type',[4,5])
			->where(function($query) {
				$query->where('request_supplies.is_request_purchase_order', '!=', 2)
					  ->orWhereNull('request_supplies.is_request_purchase_order');
			})
			->where(function ($query) {
				$query->where('request_supplies.action_type', '!=', 5)
					  ->orWhereNull('purchase_order.id');
			})
			->select(
				'request_person.first_name as requested_first_name',
				'request_person.middle_name as requested_middle_name',
				'request_person.last_name as requested_last_name',
				'request_person.signature as requested_signature',
				'approve_person.first_name as approved_first_name',
				'approve_person.middle_name as approved_middle_name',
				'approve_person.last_name as approved_last_name',
				'approve_person.signature as approved_signature',
				'inventory_name.name',
				'request_supplies.request_quantity',
				'request_supplies.release_supplies_qty',
				'request_supplies.date',
				'request_supplies.action_type',
				'request_supplies.is_purchase_order',
				'purchase_order.status as po_status',
				'purchase_order.id as purchase_order_id',
				'request_supplies.request_supplies_code',
				DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
				DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
				DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids")
			)
			->groupBy('request_supplies.request_supplies_code')
			->orderBy('request_supplies.updated_at','desc')
			->get();

			$datatable = $get_request_supplies->map(function ($request) {
				$statusText = ($request->action_type == 4) ? 'For Release' :
					(($request->action_type == 5) ? 'For Pick Up' :
					(($request->action_type == 6) ? 'Done Release' : ''));
		
				$statusBadgeClass = ($request->action_type == 4) ? 'badge-soft-warning' :
					(($request->action_type == 5) ? 'badge-soft-success' :
					(($request->action_type == 6) ? 'badge-soft-primary' : 'badge-soft-secondary'));
		
				if ($request->action_type == 6) {
					if ($request->po_status == 1) {  
						$approveButton = '<button type="button" class="btn btn-warning btn-sm text-white processPoBtn" 
											data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
											<span class="fa fa-truck"></span> Process PO
										</button>';
					} else {
						$approveButton = '<button type="button" class="btn btn-primary btn-sm text-white" disabled style="margin: 4px;">
											<span class="fa fa-check"></span> Done Release
										</button>';
					}
				} elseif ($request->action_type == 5) {
					$approveButton = '<button type="button" class="btn btn-success btn-sm text-white forReleaseBtn" 
										data-request_supplies_id="['. $request->request_supplies_ids .']" style="margin: 4px;">
										<span class="fa fa-check"></span> For Pick Up
									</button>';
				} elseif ($request->is_purchase_order == 1){
					$approveButton = '<button type="button" class="btn btn-warning btn-sm text-white approvedPoBtn" 
											data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
											<span class="fa fa-box"></span> Approved PO
										</button>';
				}
				 else {
					$approveButton = '<a type="button" class="btn btn-success btn-sm text-white approvedBtn" 
										data-request_supplies_id="['. $request->request_supplies_ids .']"  style="margin: 4px;">
										<span class="fa fa-check"></span> Approve
									</a>';
				}
		
				// $requestedBy = strtoupper(trim(
				// 	$request->requested_first_name . ' ' .
				// 	($request->requested_middle_name ? $request->requested_middle_name . ' ' : '') .
				// 	$request->requested_last_name
				// ));
		
				$approvedBy = strtoupper(trim(
					$request->approved_first_name . ' ' .
					($request->approved_middle_name ? $request->approved_middle_name . ' ' : '') .
					$request->approved_last_name
				));
				$signaturePath = asset($request->requested_signature);
       		    $signatureImage = $request->requested_signature ? '<img src="' . $signaturePath . '" width="150" height="75">' : '';
				$needed = $request->purchase_order_id ? ($request->request_quantity - $request->release_supplies_qty) : null;
		
				return [
					'requested_by' => '<a data-request_supplies_id="['. $request->request_supplies_ids .']"  data-request_supplies_code="'.$request->request_supplies_code.'"  title="Click to view details" 
					style="text-decoration: underline; cursor: pointer; color: #4620b1 !important;" 
					class="viewDetail">'.strtoupper(trim($request->requested_first_name . ' ' . ($request->requested_middle_name ? $request->requested_middle_name . ' ' : '') . $request->requested_last_name)).'</a>',
					'item' => $request->item_names,
					'quantity' => $request->request_quantities,
					// 'release' => $request->release_supplies_qty,
					// 'needed' => $needed,
					'date' => Carbon::parse($request->date)->format('F j, Y'),
					'status' => '<small class="badge fw-semi-bold rounded-pill status ' . $statusBadgeClass . '">' . $statusText . '</small>',
					'action' => $approveButton,
				];
			});
		

		return response()->json($datatable);
	}

	public function GetPurchaseOrderReleaseData()
	{
		$gen_user = Auth::user()->id;

		$user = User::find($gen_user);

		$get_request_supplies = RequestSupplies::join('inventory', 'request_supplies.inventory_id', '=', 'inventory.id')
			->join('inventory_name', 'inventory.inv_name_id', '=', 'inventory_name.id')
			->join('users as request_user', 'request_supplies.requested_by', '=', 'request_user.id')
			->join('person as request_person', 'request_user.person_id', '=', 'request_person.id')
			->leftJoin('users as approve_user', 'request_supplies.approved_by', '=', 'approve_user.id')
			->leftJoin('person as approve_person', 'approve_user.person_id', '=', 'approve_person.id')
			->leftjoin('purchase_order','request_supplies.id','=','purchase_order.request_supplies_id')
			->where(function ($query) {
				$query->whereNotIn('request_supplies.action_type', [3, 4, 5])
					  ->orWhere(function ($query) {
						  $query->where('request_supplies.action_type', 4)
								->whereNotNull('request_supplies.is_request_purchase_order')
								->where('request_supplies.is_request_purchase_order', 2);
					  })
					  ->orWhere(function ($query) {
						$query->where('request_supplies.action_type', 5)
							  ->whereNotNull('request_supplies.purchase_order_id');
					});
			})
			->where(function ($query) {
				$query->where('request_supplies.action_type', '!=', 6)
					  ->orWhere(function ($query) {
						  $query->where('request_supplies.action_type', 6)
								->where('purchase_order.status', 1);
					  });
			})
			
			
			
			->select(
				// 'request_supplies.id',
				'request_person.first_name as requested_first_name',
				'request_person.middle_name as requested_middle_name',
				'request_person.last_name as requested_last_name',
				'request_person.signature as requested_signature',
				'approve_person.first_name as approved_first_name',
				'approve_person.middle_name as approved_middle_name',
				'approve_person.last_name as approved_last_name',
				'approve_person.signature as approved_signature',
				'inventory_name.name',
				'request_supplies.request_quantity',
				'request_supplies.release_supplies_qty',
				'request_supplies.date',
				'request_supplies.action_type',
				'request_supplies.is_purchase_order',
				'purchase_order.status as po_status',
				'purchase_order.id as purchase_order_id',
				'request_supplies.request_supplies_code',
				DB::raw("GROUP_CONCAT(DISTINCT inventory_name.name ORDER BY inventory_name.name ASC SEPARATOR ' / ') as item_names"),
				DB::raw("GROUP_CONCAT(request_supplies.request_quantity ORDER BY inventory_name.name ASC SEPARATOR ' / ') as request_quantities"),
				DB::raw("GROUP_CONCAT(request_supplies.id ORDER BY request_supplies.id ASC) as request_supplies_ids")
			)
			->groupBy('request_supplies.request_supplies_code')
			->orderBy('request_supplies.updated_at','desc')
			->get();

			$datatable = $get_request_supplies->map(function ($request) {
				$statusText = ($request->action_type == 4) ? 'For Release' :
					(($request->action_type == 5) ? 'For Pick Up' :
					(($request->action_type == 6) ? 'Done Release' : ''));
		
				$statusBadgeClass = ($request->action_type == 4) ? 'badge-soft-warning' :
					(($request->action_type == 5) ? 'badge-soft-success' :
					(($request->action_type == 6) ? 'badge-soft-primary' : 'badge-soft-secondary'));
		
				if ($request->action_type == 6) {
					if ($request->po_status == 1) {  
						$approveButton = '<button type="button" class="btn btn-warning btn-sm text-white processPoBtn" 
											data-request_supplies_id="['. $request->request_supplies_ids .']" style="margin: 4px;">
											<span class="fa fa-truck"></span> Process PO
										</button>';
					} else {
						$approveButton = '<button type="button" class="btn btn-primary btn-sm text-white" disabled style="margin: 4px;">
											<span class="fa fa-check"></span> Done Release
										</button>';
					}
				} elseif ($request->action_type == 5) {
					$approveButton = '<button type="button" class="btn btn-success btn-sm text-white forReleaseBtn" 
										data-request_supplies_id="['. $request->request_supplies_ids .']" style="margin: 4px;">
										<span class="fa fa-check"></span> For Pick Up
									</button>';
				} elseif ($request->is_purchase_order == 1){
					$approveButton = '<button type="button" class="btn btn-warning btn-sm text-white approvedPoBtn" 
											data-request_supplies_id="['. $request->request_supplies_ids .']" style="margin: 4px;">
											<span class="fa fa-box"></span> Approved PO
										</button>';
				}
				 else {
					$approveButton = '<a type="button" class="btn btn-success btn-sm text-white approvedBtn disabled" 
										data-request_supplies_id="' . $request->id . '" style="margin: 4px;">
										<span class="fa fa-check"></span> Waiting for Approval
									</a>';
				}
		
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
				$signaturePath = asset($request->requested_signature);
       		    $signatureImage = $request->requested_signature ? '<img src="' . $signaturePath . '" width="150" height="75">' : '';
				$needed = $request->purchase_order_id ? ($request->request_quantity - $request->release_supplies_qty) : null;
		
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

	public function checkInventory(Request $request)
	{
		try {
			// $get_request_supplies = RequestSupplies::find($request->request_supplies_id);
	
			// if (!$get_request_supplies) {
			// 	return response()->json([
			// 		'status' => 'failed',
			// 		'message' => 'Request Supplies not found.'
			// 	]);
			// }

			$request_supplies_ids = is_array($request->request_supplies_ids) ? 
				$request->request_supplies_ids : 
				[$request->request_supplies_ids];

			$get_request_supplies = RequestSupplies::whereIn('id', $request_supplies_ids)->first();

			if (!$get_request_supplies) {
				return response()->json([
					'status' => 'failed',
					'message' => 'Requested supplies not found.'
				]);
			}

			$request_quantity = $get_request_supplies->request_quantity;

			// Ensure inventory_id is treated as an array
			$inventory_ids = is_array($get_request_supplies->inventory_id) ? 
				$get_request_supplies->inventory_id : 
				[$get_request_supplies->inventory_id];

			$inventory = Inventory::whereIn('id', $inventory_ids)->first();

			if (!$inventory) {
				return response()->json([
					'status' => 'failed',
					'message' => 'Inventory not found.'
				]);
			}

			$inv_quantity = $inventory->inv_quantity;

			return response()->json([
				'status' => 'success',
				'inventory_not_enough' => $request_quantity > $inv_quantity, 
				'current_inventory' => $inv_quantity
			]);

	
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'failed',
				'message' => 'An error occurred while checking inventory.',
				'error' => $e->getMessage()
			]);
		}
	}
	

	public function GetApprovedRequest(Request $request)
	{
		try {
			// $get_request_supplies = RequestSupplies::find($request->request_supplies_id);
			// if (!$get_request_supplies) {
			// 	return response()->json([
			// 		'status' => 'failed',
			// 		'message' => 'Request Supplies not found.'
			// 	]);
			// }

			$request_supplies_ids = is_array($request->request_supplies_ids) 
			? $request->request_supplies_ids 
			: [$request->request_supplies_ids];

		$get_request_supplies = RequestSupplies::whereIn('id', $request_supplies_ids)->get();

		$insufficient_inventory = false;
		$inventory_updates = [];

		foreach ($get_request_supplies as $request_supply) {
			$inventory = Inventory::where('id', $request_supply->inventory_id)->first();
			
			if (!$inventory) {
				return response()->json([
					'status' => 'failed',
					'message' => 'Inventory not found for one or more items.'
				]);
			}

			$inv_quantity = $inventory->inv_quantity;
			$request_quantity = $request_supply->request_quantity;

			if ($request_quantity > $inv_quantity) {
				$release_supplies_qty = $inv_quantity;
				$inventory->inv_quantity = 0;
				$inventory->save();

				// Create or retrieve Purchase Order
				$purchase_order = PurchaseOrder::firstOrCreate([
					'request_supplies_id' => $request_supply->id,
					'requested_by' => $request_supply->requested_by,
					'status' => 1
				]);

				$request_supply->purchase_order_id = $purchase_order->id;
				$insufficient_inventory = true;
			} else {
				$new_inv_quantity = $inv_quantity - $request_quantity;
				$inventory->inv_quantity = $new_inv_quantity;
				$inventory->save();

				$release_supplies_qty = $request_quantity;
			}

			$request_supply->release_supplies_qty = $release_supplies_qty;
			$request_supply->action_type = 5;
			$request_supply->save();

			$inventory_updates[] = [
				'item_id' => $request_supply->id,
				'new_inventory_quantity' => $inventory->inv_quantity
			];
		}

		return response()->json([
			'status' => 'success',
			'message' => $insufficient_inventory 
				? 'Some requests were approved, but inventory was insufficient for certain items.'
				: 'All Request Supplies Approved successfully.',
			'inventory_updates' => $inventory_updates
		]);

	
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'failed',
				'message' => 'An error occurred while approving the request.',
				'error' => $e->getMessage()
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
			
			$request_supplies_ids = is_array($request->request_supplies_ids) 
				? $request->request_supplies_ids 
				: [$request->request_supplies_ids];
			
			$get_request_supplies = RequestSupplies::whereIn('id', $request_supplies_ids)->get();
			
			if ($get_request_supplies->isEmpty()) {
				return response()->json([
					'status' => 'failed',
					'message' => 'Request Supplies not found.'
				]);
			}
			
			$inventory_updates = [];
			
			foreach ($get_request_supplies as $request_supply) {
				$inventory = Inventory::where('id', $request_supply->inventory_id)->first();
				
				if (!$inventory) {
					return response()->json([
						'status' => 'failed',
						'message' => 'Inventory not found for one or more items.'
					]);
				}
			
				$inv_quantity = $inventory->inv_quantity;
				$request_quantity = $request_supply->request_quantity;
			
				$purchase_order = PurchaseOrder::where('request_supplies_id', $request_supply->id)
					->where('status', 2)
					->first();
			
				if ($purchase_order) {
					$new_inv_quantity = max(0, $inv_quantity - $request_quantity);
					$inventory->inv_quantity = $new_inv_quantity;
					$inventory->save();
				}
			
				$request_supply->action_type = 6;
				$request_supply->release_date = Carbon::now();
				$request_supply->save();
			
				$inventory_updates[] = [
					'item_id' => $request_supply->id,
					'new_inventory_quantity' => $inventory->inv_quantity
				];
			}
			
			return response()->json([
				'status' => 'success',
				'message' => 'Request Supplies Approved successfully.',
				'inventory_updates' => $inventory_updates
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
	

	public function ForApprovedPOSupplies(Request $request)
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


			$request_supplies_ids = is_array($request->request_supplies_ids) 
				? $request->request_supplies_ids 
				: [$request->request_supplies_ids];

			$get_request_supplies = RequestSupplies::whereIn('id', $request_supplies_ids)->get();

			if ($get_request_supplies->isEmpty()) {
				return response()->json([
					'status' => 'failed',
					'message' => 'Request supplies not found.'
				]);
			}

			$inventory_updates = [];

			foreach ($get_request_supplies as $request_supply) {
				$inventory = Inventory::where('id', $request_supply->inventory_id)->first();
				
				if (!$inventory) {
					return response()->json([
						'status' => 'failed',
						'message' => 'One or more inventory items not found.'
					]);
				}

	
				$inventory->inv_quantity += $request_supply->request_quantity;
				$inventory->save();
				$request_supply->action_type = 6;
				$request_supply->release_date = Carbon::now();
				$request_supply->is_purchase_order = 0; 
				$request_supply->save();

				$inventory_updates[] = [
					'item_id' => $request_supply->id,
					'updated_inventory_quantity' => $inventory->inv_quantity
				];
			}

			return response()->json([
				'status' => 'success',
				'message' => 'Purchase Orders Approved, and inventory updated successfully.',
				'inventory_updates' => $inventory_updates
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


	public function ForProcessPO(Request $request)
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

	
			$request_supplies_ids = is_array($request->request_supplies_ids) 
				? $request->request_supplies_ids 
				: [$request->request_supplies_ids];

			$get_request_supplies = RequestSupplies::whereIn('id', $request_supplies_ids)->get();

			if ($get_request_supplies->isEmpty()) {
				return response()->json([
					'status' => 'failed',
					'message' => 'Request Supplies not found.'
				]);
			}

			$inventory_updates = [];

			foreach ($get_request_supplies as $request_supply) {
				$request_quantity = $request_supply->request_quantity;
				$release_supplies_qty = $request_supply->release_supplies_qty;
				$needed_quantity = $request_quantity - $release_supplies_qty;

				$inventory = Inventory::where('id', $request_supply->inventory_id)->first();

				if (!$inventory) {
					return response()->json([
						'status' => 'failed',
						'message' => 'Inventory not found for one or more requests.'
					]);
				}

				$inv_quantity = $inventory->inv_quantity;

				if ($needed_quantity > $inv_quantity) {
					return response()->json([
						'status' => 'failed',
						'message' => 'Not enough inventory for request Available stock: ' . $inv_quantity,
						'available_stock' => $inv_quantity
					]);
				}

			
				$purchase_order = PurchaseOrder::where('request_supplies_id', $request_supply->id)->first();
				if ($purchase_order) {
					$purchase_order->status = 2;
					$purchase_order->save();
				}

			
				$inventory->inv_quantity -= $needed_quantity;
				$inventory->save();

				
				$request_supply->release_supplies_qty += $needed_quantity;
				$request_supply->action_type = 6;
				$request_supply->save();

				$inventory_updates[] = [
					'request_supplies_id' => $request_supply->id,
					'new_inventory_quantity' => $inventory->inv_quantity
				];
			}

			return response()->json([
				'status' => 'success',
				'message' => 'Request Supplies Approved successfully.',
				'inventory_updates' => $inventory_updates
			]);

	
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'failed',
				'message' => 'An error occurred while processing the request.',
				'error' => $e->getMessage()
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
