<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Models\Permission;

class PermissionController extends Controller {

	/**
     * Instantiate a new UserController instance.
     */
    public function __construct(){
        $this->middleware('auth');

        //$this->middleware('log', ['only' => ['fooAction', 'barAction']]);

        //$this->middleware('subscribed', ['except' => ['fooAction', 'barAction']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$roles = Permission::paginate(30);

		return view('admin.permissions.index', ['permissions' => $roles]);
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
		if(!Auth::user()->can('create.permissions')){
			return redirect('admin/permissions')->withErrors(['You are not Authorized for this action!']);
		}

		$object = new Permission;

		if (!$object->validate($request->all())){
	        return redirect('admin/permissions')->withErrors($object->errors())->withInput();
	    }

	    $object->name			= $request->get('name');
		$object->slug			= $request->get('slug');
		$object->description 	= $request->get('description');
		$object->model 			= $request->get('model');

		$object->save();

		return redirect('admin/permissions')->withSuccess(['Permission succesfuly created']);
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
		if(!Auth::user()->can('delete.permissions')){
			return redirect('admin/permissions')->withErrors(['You are not Authorized for this action!']);
		}

		$object = Permission::find($id);

		if(!$object){
			return redirect('admin/permissions')->withErrors(['Permission not found']);
		}

		$object->delete();

		return redirect('admin/permissions')->withErrors(['Permission succesfuly deleted']);
	}

}
