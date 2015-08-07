<?php namespace App\Models;

use File;
use Log;
use App\Models\IndesignModel;

class Image extends IndesignModel {

	/**
	 * Dont update my timestamps! I dont have any.
	 *
	 * @var string
	 */
	public $timestamps = false;

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'images';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules 	= [ 'image_path'  						=> 'string|max:255|unique:images,image_path',
							'image'  							=> 'required|image|max:10000|img_min_size:400,400',
					        'listing_id'  						=> 'required|numeric|exists:listings,id',
					        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['image_path'  						=> 'string|max:255|unique:images,image_path',
							'image'  							=> 'required|image|max:10000|img_min_size:400,400',
					        'listing_id'  						=> 'required|numeric|exists:listings,id',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'image_path', 
							'listing_id',
							];


	/**
    * Model events
    */
    protected static function boot() {
        parent::boot();

        static::deleted(function($image) { // after delete() method call this
        	$path 		= substr($image->image_path, 1);
			$ext 		= File::extension($path);

			$paths[]	= $path;
			$paths[] 	= str_replace('.'.$ext,'-image(full_page).'.$ext,$path);
			$paths[] 	= str_replace('.'.$ext,'-image(full_image).'.$ext,$path);
			$paths[] 	= str_replace('.'.$ext,'-image(facebook_share).'.$ext,$path);
			$paths[] 	= str_replace('.'.$ext,'-image(mini_image_2x).'.$ext,$path);
			$paths[] 	= str_replace('.'.$ext,'-image(featured_front).'.$ext,$path);
			$paths[] 	= str_replace('.'.$ext,'-image(mini_front).'.$ext,$path);
			$paths[] 	= str_replace('.'.$ext,'-image(map_mini).'.$ext,$path);

			foreach ($paths as $path) {
				if(File::exists($path)){
					File::delete($path);
					if(File::exists($path)){
						Log::error('Image couldnt be deleted: '.$path);
					}
				}
			}
        });
    }


    /**
     * Relationship with listing
     *
     * @return \App\Models\Listing
     */
	public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}