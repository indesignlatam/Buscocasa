<?php namespace App\Models;

use App\Models\IndesignModel;

class Feature extends IndesignModel {

	public $timestamps = false;
	protected $softDelete = false;
	
	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'features';

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
				        'category_id'  					=> 'required|numeric|exists:feature_categories,id',
				        'published'  					=> 'boolean',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'string|max:255',
					        'category_id'  					=> 'numeric|exists:feature_categories,id',
					        'published'  					=> 'boolean',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'category_id', 'published'];

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
	// protected $appends = ['image_url'];

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

    public function category(){
        return $this->belongsTo('App\Models\FeatureCategory', 'category_id');
    }

    public function listings(){
        return $this->belongsToMany('App\Models\Listing');
    }
}