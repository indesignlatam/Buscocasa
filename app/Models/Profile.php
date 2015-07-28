<?php namespace App\Models;

use App\Models\IndesignModel;

class Profile extends IndesignModel {
	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'profiles';

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
						'phone'     			=> 'required|string|max:20',
						'cellphone'     		=> 'string|max:25',
						'image_path'     		=> 'string|max:255',
						'position'  			=> 'string|max:25',
						'idioms'  				=> 'string|max:25',
						'description'  			=> 'string|max:25',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['user_id'     			=> 'numeric|exists:users,id',
							'phone'     			=> 'required|string|max:20',
							'cellphone'     		=> 'string|max:25',
							'image_path'     		=> 'string|max:255',
							'position'  			=> 'string|max:25',
							'idioms'  				=> 'string|max:25',
							'description'  			=> 'string|max:25',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id' ,'phone', 'cellphone', 'image_path', 'position', 'idioms', 'description'];

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
	protected $appends = ['email'];

	/**
	 * The method that appends the attribute to JSON responces.
	 *
	 * @var null or attribute
	 */

	public function getEmailAttribute(){
		return $this->user->email;
	}

	public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}