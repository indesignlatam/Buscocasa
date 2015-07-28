<?php namespace App\Models;

use App\Models\IndesignModel;

class City extends IndesignModel {

	public $timestamps = false;
	protected $softDelete = false;

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'cities';

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
				        'country_id'  					=> 'required|numeric|exists:countries,id',
					    'department_id'  				=> 'required|numeric|exists:departments,id',
					    'order'  						=> 'numeric',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  					=> 'string|max:255',
					        'country_id'  				=> 'numeric|exists:countries,id',
					        'department_id'  			=> 'numeric|exists:departments,id',
					        'order'  					=> 'numeric',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'country_id', 'department_id', 'order'];

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

	public function country(){
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function department(){
        return $this->belongsTo('App\Models\Department', 'department_id');
    }
}