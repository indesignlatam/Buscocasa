<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon, File;
use App\Models\IndesignModel;

class Listing extends IndesignModel {

	use SoftDeletes;

	protected $dates = ['deleted_at', 'featured_expires_at', 'expires_at'];

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'listings';

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
				        'image_path'					=> 'string|max:255',
						'code'     						=> 'alpha_dash|max:20|unique:listings',
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
				        	'image_path'					=> 'string|max:255',
							'code'     						=> 'alpha_dash|max:20|unique:listings',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['slug', 'broker_id', 'category_id', 'listing_type', 'listing_status', 'city_id', 'direction', 
							'latitude', 'longitude', 'title', 'description', 'price', 'rooms', 'bathrooms', 
							'garages', 'area', 'stratum', 'lot_area', 'construction_year', 'administration', 'image_path', 'main_image_id', 
							'published', 'featured', 'floor', 'district', 'code'];

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
	protected $appends = ['image_url'];

	/**
	 * The method that appends the attribute to JSON responces.
	 *
	 * @var null or attribute
	 */
	public function getImageUrlAttribute(){
		if($this->image_path){
			return asset($this->image_path);
		}
		return null;
	}

	public function image_path(){
		if($this->image_path || $this->image_path != ''){
			return $this->image_path;
		}
		return '/images/defaults/listing.jpg';
	}

	public function path(){
		return strtolower('/'.str_plural($this->listingType->name).'/'.$this->slug);
	}

	public function pathEdit(){
		return strtolower('/admin/listings/'.$this->id.'/edit');
	}

	public function hasUnconfirmedPayments(){
		if(count($this->payments) > 0){
			foreach ($this->payments as $payment) {
				if(!$payment->confirmed && !$payment->canceled && $payment->state_pol == null){
					return true;
				}
			}
		}
		return false;
	}

	/**
     * Scope a query to only include non expired listings.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query){
        return $query->where('expires_at', '>', Carbon::now());
    }

    public function scopeFeatured($query){
        return $query->where('featured_expires_at', '>', Carbon::now());
    }



    // All messages count
    public function mainImage(){
    	if(count($this->images) > 0){
	    	if($this->main_image_id){
	    		return $this->hasOne('App\Models\Image')
				      	->where('id', $this->main_image_id)
				      	->groupBy('listing_id');
	    	}else{
	    		return $this->hasOne('App\Models\Image')
		  				->where('listing_id', $this->id)
		  				->orderBy('id', 'DESC')
		  				->take(1)
		  				->groupBy('listing_id');
	    	}
	    }
	    return null;
	}
	public function getMainImageAttribute(){
		if(count($this->images) > 0){
			// if relation is not loaded already, let's do it first
			if ( ! array_key_exists('mainImage', $this->relations)) 
			$this->load('mainImage');

			$related = $this->getRelation('mainImage');

			// then return the count directly
			return $this->getRelation('mainImage');
		}else{
			$image = new Image;
			$image->image_path = '/images/defaults/listing.jpg';
			return $image;
		}
	}

	// All messages count
    public function messageCount(){
	  	return $this->hasOne('App\Models\Appointment')
			      	->selectRaw('listing_id, count(*) as aggregate')
			      	->groupBy('listing_id');
	}
	public function getMessageCountAttribute(){
		// if relation is not loaded already, let's do it first
		if ( ! array_key_exists('messageCount', $this->relations)) 
		$this->load('messageCount');

		$related = $this->getRelation('messageCount');

		// then return the count directly
		return ($related) ? (int) $related->aggregate : 0;
	}

	// Unread messages count
    public function notAnsweredMessageCount(){
	  	return $this->hasOne('App\Models\Appointment')
			      	->selectRaw('listing_id, count(*) as aggregate')
			      	->notAnswered()
			      	->groupBy('listing_id');
	}
	public function getNotAnsweredMessageCountAttribute(){
		// if relation is not loaded already, let's do it first
		if ( ! array_key_exists('notAnsweredMessageCount', $this->relations)) 
		$this->load('notAnsweredMessageCount');

		$related = $this->getRelation('notAnsweredMessageCount');

		// then return the count directly
		return ($related) ? (int) $related->aggregate : 0;
	}


	// EVENTS
	/**
    * Model events
    */
    protected static function boot() {
        parent::boot();

        static::deleting(function($listing) { // before delete() method call this
        	if($listing->forceDeleting){
		        // do in case of force delete

		        $listing->payments()->update(['listing_id' => null]);

		        // Set main image id to null to prevent mysql errors
				if($listing->main_image_id){
					$listing->main_image_id = null;
					$listing->save();
				}
				
				// Delete images	
				foreach($listing->images as $image){
					File::delete(public_path().'/'.$image->image_path);
					$image->delete();
				}

				// Delete features relations
				$ids = [];
				foreach($listing->features as $feature){
					$ids[] = $feature->id;
				}
				$listing->features()->detach($ids);
		    }
        });
    }



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

    public function features(){
        return $this->belongsToMany('App\Models\Feature');
    }

    public function images(){
        return $this->hasMany('App\Models\Image', 'listing_id');
    }

    public function payments(){
        return $this->hasMany('App\Models\Payment', 'listing_id');
    }

    public function messages(){
        return $this->hasMany('App\Models\Appointment', 'listing_id');
    }
}