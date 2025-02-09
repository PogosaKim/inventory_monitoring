<?php namespace App\Http\Controllers;

use App\Dean;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Person;
use App\Roles;
use App\SchoolDepartment;
use App\Teachers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Security\Core\Role\Role;

class Admin extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.index');
	}

	public function GetCreateUser()
	{	

		$user_role_list = Roles::all();
		$school_department_list = SchoolDepartment::all();

		return view('admin.create_user',compact('user_role_list','school_department_list'));
	}

	public function GetResetPassword()
	{
		return view('admin.reset_password');
	}

	public function find_reset_password(Request $request)
	{
		if ($request->ajax()) {
			$username = $request->input('user_name');
	
			$user = User::where('name', $username)->first();
	
			$persons = Person::where('last_name', $username)->get();
	
			$personList = $persons->map(function ($person) use ($user) {
				return [
					'first_name' => $person->first_name,
					'last_name' => $person->last_name,
					'user_id' => $user ? $user->id : null, 
				];
			});
	
			if ($user || count($persons) > 0){
				return response()->json([
					'success' => true,
					'message' => 'Data found',
					'persons' => $personList,
				]);
			} else {
				return response()->json([
					'success' => false,
					'message' => 'User or person not found',
				]);
			}
		}
	
		return view('auth.reset_password');
	}
	
	


	public function update_reset_password(Request $request)
	{
		$user = User::find($request->user_id);
	
		if ($user) {
			$user->password = Hash::make($request->new_password);
			$user->save();
	
			return response()->json([
				'success' => true,
				'message' => 'Password updated successfully',
			]);
		}
	
		return response()->json([
			'success' => false,
			'message' => 'User not found or update failed',
		]);
	}
	


	public function GetCreateUsers(Request $request)
	{
		
		try {
			$roles = [
				1 => 'pc',
				2 => 'teacher',
				3 => 'school_president',
				4 => 'finance',
				5 => 'dean'
			];
		
		
			$person = new Person();
			$person->first_name = $request->first_name;
			$person->middle_name = $request->middle_name;
			$person->last_name = $request->last_name;
			$person->signature = $this->saveSignatureImage($request->signature, $request->last_name); 
			$person->save();  

			$user = new User();
			$user->person_id = $person->id;
			$user->name = strtolower($request->last_name);
			$user->user_role_id = $request->user_role_id;
			$user->school_department_id = $request->school_department_id;
			$user->role = $roles[$request->user_role_id] ? : null;  
			$user->password = bcrypt($request->password);
			$user->save();
	
			if ($request->user_role_id == 2) {
				$teacher = new Teachers();
				$teacher->person_id = $person->id;
				$teacher->school_department_id = $user->school_department_id;
				$teacher->save();
			}
		
			if ($request->user_role_id == 5) {
				$dean = new Dean();
				$dean->person_id = $person->id;
				$dean->school_department_id = $user->school_department_id;
				$dean->save();
			}
		
			return response()->json(['success' => true, 'message' => 'User created successfully!']);
		
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
		}
		
	}

	private function saveSignatureImage($base64Image, $lastName)
	{
		$path = public_path('assets/signature/');
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		$image = str_replace('data:image/png;base64,', '', $base64Image);
		$image = str_replace(' ', '+', $image);
		$fileName = $lastName . '.jpg';
		$filePath = $path . $fileName;

		file_put_contents($filePath, base64_decode($image));

		return 'assets/signature/' . $fileName; 
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
