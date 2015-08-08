<?php namespace App\Models;

use App\Models\IndesignModel;

class District extends IndesignModel {

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
    protected $table = 'districts';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'city_id'  						=> 'required|numeric|exists:cities,id',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  					=> 'string|max:255',
					        'city_id'  					=> 'numeric|exists:cities,id',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'city_id',
							];


	/**
     * Relationship with city
     *
     * @return \App\Models\City
     */
	public function city(){
        return $this->belongsTo('App\Models\City', 'city_id');
    }
}