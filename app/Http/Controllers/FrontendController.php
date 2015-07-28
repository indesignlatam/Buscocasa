<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FrontendController extends Controller {

	/**
	 * Show the nosotros article
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function nosotros(){
		//
		return view('articles.nosotros');
	}

	/**
	 * Show the publica article
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function publica(){
		//
		return view('articles.publica');
	}

}
