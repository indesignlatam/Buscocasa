<?php namespace App\Models;

use App\Models\IndesignModel;

class Like extends IndesignModel {

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'likes';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['user_id'     				=> 'required|numeric|exists:users,id',
						'listing_id'     			=> 'required|numeric|exists:listings,id',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['user_id'     				=> 'required|numeric|exists:users,id',
							'listing_id'     			=> 'required|numeric|exists:listings,id',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'user_id', 
							'listing_id',
							];



    public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}