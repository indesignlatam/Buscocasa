<?php namespace App\Models;

use Carbon;
use App\Models\IndesignModel;

class ArchivedListing extends IndesignModel {

	protected $dates = [];

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'archived_listings';

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
	protected $rules = ['slug'     						=> 'alpha_dash|max:255|unique:listings,slug',
						'broker_id'     				=> 'required|numeric|exists:users,id',
						'category_id'     				=> 'required|numeric|exists:categories,id',
						'listing_type'     				=> 'required|numeric|exists:listing_types,id',
						'listing_status'  				=> 'numeric|exists:listing_statuses,id',
				        'city_id'     					=> 'required|numeric|exists:cities,id',
				        'direction'  					=> 'required|string|max:255',
				        'latitude'  					=> 'required|numeric',
				        'longitude'  					=> 'required|numeric',
				        'title'  						=> 'string|max:255',
				        'description'  					=> 'string|max:255',
				        'price'  						=> 'required|numeric|min:0',
				        'stratum'  						=> 'required|numeric|min:0|max:6',
				        'rooms'  						=> 'numeric',
				        'bathrooms'  					=> 'numeric',
				        'garages'  						=> 'numeric',
				        'area'  						=> 'numeric',
				        'lot_area'  					=> 'numeric',
				        'construction_year'  			=> 'numeric|min:1800|max:2040',
				        'administration'  				=> 'numeric|min:0',
				        'image'  						=> 'image|max:2000',
				        'main_image_id'  				=> 'numeric|exists:images,id',
				        'published'  					=> 'boolean',
				        'featured'  					=> 'boolean',
				        'floor'  						=> 'numeric|min:1|max:100',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['slug'     						=> 'string|max:255|unique:listings,slug',
							'broker_id'     				=> 'numeric|exists:users,id',
							'category_id'     				=> 'numeric|exists:categories,id',
							'listing_type'     				=> 'numeric|exists:listing_types,id',
							'listing_status'  				=> 'numeric|exists:listing_statuses,id',
					        'city_id'     					=> 'numeric|exists:cities,id',
					        'direction'  					=> 'string|max:255',
					        'latitude'  					=> 'numeric',
					        'longitude'  					=> 'numeric',
					        'title'  						=> 'string|max:255',
					        'description'  					=> 'string',
					        'price'  						=> 'numeric|min:0',
				        	'stratum'  						=> 'numeric|min:1|max:6',
					        'rooms'  						=> 'numeric',
					        'bathrooms'  					=> 'numeric',
					        'garages'  						=> 'numeric',
					        'area'  						=> 'numeric|min:0',
					        'lot_area'  					=> 'numeric',
					        'construction_year'  			=> 'numeric',
					        'administration'  				=> 'numeric|min:0',
					        'image_path'  					=> 'string|max:255',
				        	'main_image_id'  				=> 'numeric|exists:images,id',
					        'published'  					=> 'boolean',
					        'featured'  					=> 'boolean',
					        'floor'  						=> 'numeric',
					        'district'  					=> 'string|max:200|min:3',
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
	 * The attributes that are hidden to JSON responces.
	 *
	 * @var array
	 */
	protected $hidden = ['created_at', 'image_path'];

	/**
	 * The attributes that are appended to JSON responces.
	 *
	 * @var array
	 */
	protected $appends = [];





	public function broker(){
        return $this->belongsTo('App\User', 'broker_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function district(){
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    public function listingType(){
        return $this->belongsTo('App\Models\ListingType', 'listing_type');
    }

    public function listingStatus(){
        return $this->belongsTo('App\Models\ListingStatus', 'listing_status');
    }

    public function featuredType(){
        return $this->belongsTo('App\Models\FeaturedType', 'featured_type');
    }

    public function payments(){
        return $this->hasMany('App\Models\Payment', 'listing_id');
    }

    public function messages(){
        return $this->hasMany('App\Models\Appointment', 'listing_id');
    }
}