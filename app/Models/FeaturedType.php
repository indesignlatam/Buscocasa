<?php namespace App\Models;

use App\Models\IndesignModel;

class FeaturedType extends IndesignModel {

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
    protected $table = 'featured_types';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules 	= [ 'name'  						=> 'required|string|max:255',
					        'image_path'  					=> 'string|max:255',
					        'description'  					=> 'string',
					        'price'  						=> 'numeric',
					        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'required|string|max:255',
					        'image_path'  					=> 'string|max:255',
					        'description'  					=> 'string',
					        'price'  						=> 'numeric',
				        	];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'image_path', 
							'description', 
							'price',
							];


	/**
     * Relationship with listings
     *
     * @return \App\Models\Listing
     */
	public function listings(){
        return $this->hasMany('App\Models\Listing', 'featured_type');
    }
}