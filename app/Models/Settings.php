<?php namespace App;

use App\IndesignModel;

class Settings extends IndesignModel {
	
	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'settings';

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
	protected $rules = ['user_id'     					=> 'required|numeric|exists:users',
						'app_name'  					=> 'required|string|max:255',
						'environment'  					=> 'string|max:255',
						'ios_development_certificate'  	=> 'max:100|mimes:pem',
						'ios_production_certificate'  	=> 'max:100|mimes:pem',
						'ios_development_passphrase'  	=> 'string|max:255',
						'ios_production_passphrase'  	=> 'string|max:255',
						'android_api_key'  				=> 'string|max:255',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['app_name'  					=> 'required|string|max:255',
							'environment'  					=> 'string|max:255',
							'ios_development_certificate'  	=> 'max:100',
							'ios_development_certificate'  	=> 'max:100',
							'ios_development_passphrase'  	=> 'string|max:255',
							'ios_production_passphrase'  	=> 'string|max:255',
							'android_api_key'  				=> 'string|max:255',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'app_name', 'environment', 'ios_development_certificate', 
							'ios_production_certificate', 'ios_development_passphrase', 'ios_production_passphrase',
							'android_api_key'];

	/**
	 * The attributes that are hidden to JSON responces.
	 *
	 * @var array
	 */
	protected $hidden = ['created_at'];

	/**
	 * The attributes that are appended to JSON responces.
	 *
	 * @var array
	 */
	//protected $appends = ['image_full_url'];

	/**
	 * The method that appends the attribute to JSON responces.
	 *
	 * @var null or attribute
	 */
	// public function getImageFullUrlAttribute(){
	// 	if($this->image_url){
	// 		return asset($this->image_url);
	// 	}
	// 	return null;
	// }

	public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

}
