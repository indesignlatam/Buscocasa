<?php namespace App\Models;

use App\Models\IndesignModel;

class Payment extends IndesignModel {
	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'payments';

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
	protected $rules = ['user_id'     			=> 'required|numeric|exists:users,id',
						'listing_id'     		=> 'required|numeric|exists:listings,id',
						'featured_id'     		=> 'required|numeric|exists:featured_types,id',
						'description'     		=> 'required|string|max:255',
						'reference_code'     	=> 'required|string|max:50|unique:payments',
						'amount'     			=> 'required|numeric|min:1',
						'tax'  					=> 'required|numeric|min:0',
						'tax_return_base'  		=> 'required|numeric|min:0',
						'currency'  			=> 'string|max:5',
						'signature'  			=> 'required|string|max:255',
						'confirmed'  			=> 'boolean',
						'canceled'  			=> 'boolean',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['user_id'     			=> 'required|numeric|exists:users,id',
							'listing_id'     		=> 'required|numeric|exists:listings,id',
							'featured_id'     		=> 'required|numeric|exists:featured_types,id',
							'description'     		=> 'required|string|max:255',
							'reference_code'     	=> 'required|string|max:50|unique:payments',
							'amount'     			=> 'required|numeric|min:1',
							'tax'  					=> 'required|numeric|min:0',
							'tax_return_base'  		=> 'required|numeric|min:0',
							'currency'  			=> 'string|max:5',
							'signature'  			=> 'required|string|max:255',
							'confirmed'  			=> 'boolean',
							'canceled'  			=> 'boolean',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'user_id', 'listing_id', 'featured_id', 'description', 'reference_code', 'amount', 'tax', 'tax_return_base', 
							'currency', 'signature', 'confirmed', 'canceled'];

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
	// protected $appends = ['email'];

	/**
	 * The method that appends the attribute to JSON responces.
	 *
	 * @var null or attribute
	 */

	// public function getEmailAttribute(){
	// 	return $this->user->email;
	// }

	public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }

    public function FeaturedType(){
        return $this->belongsTo('App\Models\FeaturedType', 'featured_id');
    }
}