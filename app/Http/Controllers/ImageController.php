<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Image;
use File;
use Auth;
use Carbon;
use Settings;
use App\Models\Image as ImageModel;
use App\Models\Listing;

class ImageController extends Controller {

	/**
     * Instantiate a new ImageController instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('file_max_upload_size', ['only' => 'store']);
    }

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
		// Get the object requested
		$listing = Listing::find($request->get('listing_id'));

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->broker_id != Auth::user()->id){
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

		// Create an image object
		$image = new ImageModel;

	    $file 	= $request->file("image");
		$name 	= $listing->id.md5($request->get('title') . str_random(40)).'.'.$file->getClientOriginalExtension();
		$input 	= $request->all();
		$input['image_path'] = '/images/listings/full/'.$name;

		if (!$image->validate($input)){
	        return response()->json(['error' 	=> [$image->errors()],
	        	        			 'image' 	=> null
	        						]);
	    }

	    // Move file to temp folder
		if(!$file->move("images/temp", $name)){
			return response()->json(['error' => [trans('responses.error_saving_image')],
									 'image' => null
									]);
		}

		// Get image orientation
		$exif = exif_read_data(public_path().'/images/temp/'.$name, 'IFD0');
		$rotation = 0;
		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
		        case 8:
		            $rotation = -90;
		            break;
		        case 3:
		            $rotation = 180;
		            break;
		        case 6:
		            $rotation = 90;
		            break;
		    }
		}

		// Rotate the image if necessary
		if($rotation != 0){
			$img = Image::open(public_path().'/images/temp/'.$name);
			$img->rotate($rotation);
			$img->save('images/temp/'.$name);
		}
		
		// Crop image, watermark
		$img 			= Image::make('/images/temp/'.$name, ['width' => 800, 'height' => 540, 'crop' => true]);
		$watermark 		= Image::open(public_path().'/images/watermark_contrast.png');// Or use watermark.png for color watermark
		$size      		= $img->getSize();
		$wSize     		= $watermark->getSize();
		$bottomRight 	= new \Imagine\Image\Point($size->getWidth() - $wSize->getWidth()-15, $size->getHeight() - $wSize->getHeight()-15);
		$img->paste($watermark, $bottomRight);
		$img->save('images/listings/full/'.$name);

		// Delete the temp file
		File::delete(public_path().'/images/temp/'.$name);

		// Set this image as main if none set on listing
		if(count($listing->images) == 0){
			$listing->image_path = 'images/listings/full/'.$name;
			$listing->save();
		}

		// Create the image
		$image = $image->create($input);

		// Return ajax response with image and success message
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
		// Get the object requested
		$image = ImageModel::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$image || $image->listing->broker_id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Null image_path and main_image_id if deleted image is main image
		if($image->listing->main_image_id == $id){
			$image->listing->main_image_id = null;
			$image->listing->image_path = null;
			$image->listing->save();
		}else if(substr($image->listing->image_path, -40) == substr($image->image_path, -40)){
			$image->listing->main_image_id = null;
			$image->listing->image_path = null;
			$image->listing->save();
		}

		// Persist listing after deleting image
		$listing = $image->listing;
		
		// Delete image
		$image->delete();

		// If no image_path and there are mores images set first as image_path
		if($listing->image_path == null && count($listing->images) > 0){
			$listing->main_image_id = null;
			$listing->image_path = $listing->images->first()->image_path;
			$listing->save();
		}else if(count($listing->images)){
			$listing->main_image_id = null;
			$listing->image_path = null;
			$listing->save();
		}

		// Return ajax response
		return response()->json(['images_count' => count($listing->images), 
								 'success'		=> trans('admin.image_deleted_succesfuly'),
								 ]);
	}

}