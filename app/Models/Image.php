<?php namespace App\Models;

use File;
use App\Models\IndesignModel;

class Image extends IndesignModel {

	public $timestamps = false;
	protected $softDelete = false;

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'images';

    /**
	 * The primary key of the table.
	 *
	 * @var string
	 */
	//protected $primaryKey = 'pid';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['image_path'  						=> 'string|max:255|unique:images,image_path',
						'image'  							=> 'required|image|max:10000|img_min_size:400,400',
				        'listing_id'  						=> 'required|numeric|exists:listings,id',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['image_path'  						=> 'string|max:255|unique:images,image_path',
							'image'  							=> 'image|max:10000|img_min_size:400,400',
					        'listing_id'  						=> 'numeric|exists:listings,id',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['image_path', 'listing_id'];

	/**
	 * The attributes that are hidden to JSON responces.
	 *
	 * @var array
	 */
	protected $hidden = ['created_at', 'deleted_at'];

	/**
	 * The attributes that are appended to JSON responces.
	 *
	 * @var array
	 */
	//protected $appends = ['image_url'];

	/**
	 * The method that appends the attribute to JSON responces.
	 *
	 * @var null or attribute
	 */
	// public function getImageUrlAttribute(){
	// 	if($this->image_path){
	// 		return asset($this->image_path);
	// 	}
	// 	return null;
	// }

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
						return response()->json(['error' => trans('responses.error_deleting_image')]);
					}
				}
			}
        });
    }

	public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}