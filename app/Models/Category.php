<?php namespace App\Models;

use App\Models\IndesignModel;

class Category extends IndesignModel {
	
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
    protected $table = 'categories';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules 	= [ 'name'  						=> 'required|string|max:255',
					        'image'  						=> 'image|max:2000',
					        'image_path'  					=> 'string|max:255',
					        'published'  					=> 'boolean',
					        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'string|max:255',
					        'image'  						=> 'image|max:2000',
					        'image_path'  					=> 'string|max:255',
					        'published'  					=> 'boolean',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'image_path', 
							'published',
							];


	/**
     * Relationship with listings
     *
     * @return \App\Models\Listing
     */
	public function listings(){
        return $this->hasMany('App\Models\Listing', 'category_id');
    }
}