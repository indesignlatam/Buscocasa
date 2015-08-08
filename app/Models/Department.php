<?php namespace App\Models;

use App\Models\IndesignModel;

class Department extends IndesignModel {

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
    protected $table = 'departments';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules 	= [ 'name'  					=> 'required|string|max:255',
					        'country_id'  				=> 'required|numeric|exists:countries,id',
					        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  					=> 'string|max:255',
				        	'country_id'  				=> 'required|numeric|exists:countries,id',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'country_id'];


	/**
     * Relationship with country
     *
     * @return \App\Models\Country
     */
	public function country(){
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    /**
     * Relationship with cities
     *
     * @return \App\Models\City
     */
    public function cities(){
        return $this->hasMany('App\Models\City', 'department_id');
    }
}