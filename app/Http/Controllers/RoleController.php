<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Role;

class RoleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$roles = Role::paginate(30);

		return view('admin.roles.index', ['roles' => $roles]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		//
		$object = new Role;

		if (!$object->validate($request->all())){
	        return redirect('/admin/roles')->withErrors($object->errors())->withInput();
	    }

	    $object->name			= $request->get('name');
		$object->slug			= $request->get('slug');
		$object->description 	= $request->get('description');
		$object->level 			= $request->get('level');

		$object->save();

		return redirect('admin/roles')->withSuccess(['Role succesfuly created']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		//
		$object = Role::find($id);

		if(!$object){
			return redirect('admin/roles')->withErrors(['Role not found']);
		}

		$object->delete();

		return redirect('admin/roles')->withErrors(['Role succesfuly deleted']);
	}

}
