<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model{

	/**
	 * Dont update my timestamps! I dont have any.
	 *
	 * @var string
	 */
	public $timestamps = false;

	/**
	 * Dont soft delete me! Kill me completly.
	 *
	 * @var string
	 */
	protected $softDelete = false;


	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'countries';
	

    /**
     * Relationship with cities
     *
     * @return \App\Models\City
     */
	public function cities(){
        return $this->hasMany('App\Models\City', 'country_id');
    }
}