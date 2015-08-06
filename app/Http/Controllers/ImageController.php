<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Image, File, Auth, Carbon, Settings;
use App\Models\Image as ImageModel, App\Models\Listing;

class ImageController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
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
		$listing = Listing::find($request->get('listing_id'));

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission'.$id)]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Image number limit
		if(!$listing->broker->confirmed){
			if(count($listing->images) >= Settings::get('unconfirmed_image_limit', 2)){
				return response()->json(['error' => trans('responses.image_limit'),
										 'image' => null
										 ]);
			}
		}elseif($listing->featured_type && $listing->featured_expires_at && $listing->featured_expires_at > Carbon::now()){
			if(count($listing->images) >= Settings::get('featured_image_limit', 20)){
				return response()->json(['error' => trans('responses.image_limit'),
										 'image' => null
										 ]);
			}
		}else{
			if(count($listing->images) >= Settings::get('free_image_limit', 10)){
				return response()->json(['error' => trans('responses.image_limit'),
										 'image' => null
										 ]);
			}
		}

		


		$image = new ImageModel;

		$input = $request->all();

	    $file = $request->file("image");
		$name = md5($request->get('title') . str_random(40)).'.'.$file->getClientOriginalExtension();
		$input['image_path'] = '/images/listings/full/'.$name;

		if (!$image->validate($input)){
	        return response()->json(['error' 	=> [$image->errors()],
	        	        			 'image' 	=> null
	        						]);
	    }

		if($file->move("images/temp", $name) == null){
			return response()->json(['error' => [trans('responses.error_saving_image')],
									 'image' => null
									]);
		}

		// Crop image and watermark it
		$img 			= Image::make('/images/temp/'.$name, ['width' => 800, 'height' => 540, 'crop' => true]);
		$watermark 		= Image::open(public_path().'/images/watermark_contrast.png');// Or use watermark.png for color watermark
		$size      		= $img->getSize();
		$wSize     		= $watermark->getSize();
		$bottomRight 	= new \Imagine\Image\Point($size->getWidth() - $wSize->getWidth()-15, $size->getHeight() - $wSize->getHeight()-15);
		$img->paste($watermark, $bottomRight);
		$img->save('images/listings/full/'.$name);

		File::delete(public_path().'/images/temp/'.$name);

		// Solves bug where image is not show if the listing is not saved #125
		$setMainImage = false;
		if(count($listing->images) == 0){
			$setMainImage = true;
			$listing->image_path = 'images/listings/full/'.$name;
			$listing->save();
		}

		$image = $image->create($input);

		return response()->json(['image' 	=> $image,
								 'success'	=> trans('admin.image_uploaded_succesfuly'),
								 ]);
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
	public function destroy($id, Request $request){
		//
		$image 		= ImageModel::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$image || $image->listing->broker->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		if($image->listing->main_image_id == $id){
			$image->listing->main_image_id = null;
			$image->listing->image_path = null;
			$image->listing->save();
		}else if(substr($image->listing->image_path, -40) == substr($image->image_path, -40)){
			$image->listing->main_image_id = null;
			$image->listing->image_path = null;
			$image->listing->save();
		}

		$listing = $image->listing;
		
		$image->delete();

		if($listing->image_path == null && count($listing->images) > 0){
			$image->listing->main_image_id 	= null;
			$listing->image_path = $listing->images->first()->image_path;
			$listing->save();
		}else if(count($listing->images)){
			$image->listing->main_image_id 	= null;
			$listing->image_path 			= null;
			$listing->save();
		}

		return response()->json(['images_count' => count($listing->images), 
								 'success'		=> trans('admin.image_deleted_succesfuly'),
								 ]);
	}

}