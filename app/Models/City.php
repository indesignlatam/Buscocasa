<?php namespace App\Models;

use App\Models\IndesignModel;

class City extends IndesignModel {

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
    protected $table = 'cities';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'country_id'  					=> 'required|numeric|exists:countries,id',
					    'department_id'  				=> 'required|numeric|exists:departments,id',
					    'order'  						=> 'numeric|min:0',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  					=> 'string|max:255',
					        'country_id'  				=> 'numeric|exists:countries,id',
					        'department_id'  			=> 'numeric|exists:departments,id',
					    	'order'  					=> 'numeric|min:0',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'country_id', 
							'department_id', 
							'order',
							];


	/**
     * Relationship with country
     *
     * @return \App\Models\Country
     */
	public function country(){
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    /**
     * Relationship with department
     *
     * @return \App\Models\Department
     */
    public function department(){
        return $this->belongsTo('App\Models\Department', 'department_id');
    }
}