<?php namespace App\Models;

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
						'image'  							=> 'required|image|max:6500|img_min_size:400,400',
				        'listing_id'  						=> 'required|numeric|exists:listings,id',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['image_path'  						=> 'string|max:255|unique:images,image_path',
							'image'  							=> 'image|max:6500|img_min_size:400,400',
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

	public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}