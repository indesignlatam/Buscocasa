<?php namespace App\Models;

use App\Models\IndesignModel;

class ListingStatus extends IndesignModel {

	public $timestamps = false;
	protected $softDelete = false;
	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'listing_statuses';

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
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'image_path'  					=> 'string|max:255',
				        'published'  					=> 'boolean',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'string|max:255',
					        'image_path'  					=> 'string|max:255',
					        'published'  					=> 'boolean',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'image_path', 'published'];

	/**
	 * The attributes that are hidden to JSON responces.
	 *
	 * @var array
	 */
	protected $hidden = ['created_at', 'deleted_at', 'image_path'];

	/**
	 * The attributes that are appended to JSON responces.
	 *
	 * @var array
	 */
	protected $appends = ['image_url'];

	/**
	 * The method that appends the attribute to JSON responces.
	 *
	 * @var null or attribute
	 */
	public function getImageUrlAttribute(){
		if($this->image_path){
			return asset($this->image_path);
		}
		return null;
	}

	
	public function listings(){
        return $this->hasMany('App\Models\Listing', 'listing_status');
    }
}