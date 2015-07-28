<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model{

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'countries';

	public $timestamps = false;
	protected $softDelete = false;
	
	protected $visible = ['id', 'name'];

	public function cities(){
        return $this->hasMany('App\Models\City', 'country_id');
    }
}