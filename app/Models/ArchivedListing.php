<?php namespace App\Models;

use App\Models\IndesignModel;

class ArchivedListing extends IndesignModel {

	/**
     * The attributes that would be returned as dates
     *
     * @var string
     */
    protected $dates = ['created_at', 'update_at', 'expires_at'];

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'archived_listings';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
		protected $rules = ['slug'     						=> 'alpha_dash|max:255|unique:listings,slug',
							'broker_id'     				=> 'numeric|exists:users,id',
							'category_id'     				=> 'numeric|exists:categories,id',
							'listing_type'     				=> 'numeric|exists:listing_types,id',
							'listing_status'  				=> 'exists:listing_statuses,id',
					        'city_id'     					=> 'numeric|exists:cities,id',
					        'direction'  					=> 'string|max:255',
					        'latitude'  					=> 'numeric',
					        'longitude'  					=> 'numeric',
					        'title'  						=> 'string|max:255',
					        'description'  					=> 'string|max:2000',
					        'price'  						=> 'numeric|min:0',
					        'stratum'  						=> 'numeric|min:0|max:6',
					        'rooms'  						=> 'numeric',
					        'bathrooms'  					=> 'numeric',
					        'garages'  						=> 'numeric',
					        'area'  						=> 'numeric',
					        'lot_area'  					=> 'numeric',
					        'construction_year'  			=> 'numeric|min:1800|max:2040',
					        'administration'  				=> 'numeric|min:0',
					        'floor'  						=> 'numeric|min:1|max:100',
					        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['slug'     						=> 'alpha_dash|max:255|unique:listings,slug',
							'broker_id'     				=> 'numeric|exists:users,id',
							'category_id'     				=> 'numeric|exists:categories,id',
							'listing_type'     				=> 'numeric|exists:listing_types,id',
							'listing_status'  				=> 'exists:listing_statuses,id',
					        'city_id'     					=> 'numeric|exists:cities,id',
					        'direction'  					=> 'string|max:255',
					        'latitude'  					=> 'numeric',
					        'longitude'  					=> 'numeric',
					        'title'  						=> 'string|max:255',
					        'description'  					=> 'string|max:2000',
					        'price'  						=> 'numeric|min:0',
					        'stratum'  						=> 'numeric|min:0|max:6',
					        'rooms'  						=> 'numeric',
					        'bathrooms'  					=> 'numeric',
					        'garages'  						=> 'numeric',
					        'area'  						=> 'numeric',
					        'lot_area'  					=> 'numeric',
					        'construction_year'  			=> 'numeric|min:1800|max:2040',
					        'administration'  				=> 'numeric|min:0',
					        'floor'  						=> 'numeric|min:1|max:100',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'slug',
							'broker_id', 
							'category_id', 
							'listing_type', 
							'listing_status', 
							'city_id', 
							'district_id', 
							'district',
							'direction', 
							'latitude', 
							'longitude', 
							'title', 
							'description', 
							'price', 
							'rooms', 
							'bathrooms', 
							'garages', 
							'area', 
							'lot_area', 
							'floor', 
							'construction_year', 
							'administration', 
							'stratum', 
							'featured_type',
							'views',
							'expires_at',
							];



	/**
     * Relationship with user to whom the listing belonged
     *
     * @return \App\User
     */
	public function broker(){
        return $this->belongsTo('App\User', 'broker_id');
    }

    /**
     * Relationship with the category the listing belonged
     *
     * @return \App\User
     */
    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    /**
     * Relationship with the city the listing belonged
     *
     * @return \App\User
     */
    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    /**
     * Relationship with the district the listing belonged
     *
     * @return \App\User
     */
    public function district(){
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    /**
     * Relationship with the listing type the listing belonged
     *
     * @return \App\User
     */
    public function listingType(){
        return $this->belongsTo('App\Models\ListingType', 'listing_type');
    }

    /**
     * Relationship with the listing status the listing belonged
     *
     * @return \App\User
     */
    public function listingStatus(){
        return $this->belongsTo('App\Models\ListingStatus', 'listing_status');
    }

    /**
     * Relationship with the faetured type the listing belonged
     *
     * @return \App\User
     */
    public function featuredType(){
        return $this->belongsTo('App\Models\FeaturedType', 'featured_type');
    }

    /**
     * Relationship with the payments the listing had
     *
     * @return \App\User
     */
    public function payments(){
        return $this->hasMany('App\Models\Payment', 'listing_id');
    }

   	/**
     * Relationship with the messages the listing had
     *
     * @return \App\User
     */
    public function messages(){
        return $this->hasMany('App\Models\Appointment', 'listing_id');
    }
}