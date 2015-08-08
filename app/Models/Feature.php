<?php namespace App\Models;

use App\Models\IndesignModel;

class Feature extends IndesignModel {

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
    protected $table = 'features';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules 	= [ 'name'  						=> 'required|string|max:255',
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
	protected $fillable = [ 'name', 
							'category_id', 
							'published',
							];


	/**
     * Relationship with category
     *
     * @return \App\Models\Category
     */
    public function category(){
        return $this->belongsTo('App\Models\FeatureCategory', 'category_id');
    }

    /**
     * Relationship with listings
     *
     * @return \App\Models\Listing
     */
    public function listings(){
        return $this->belongsToMany('App\Models\Listing');
    }
}