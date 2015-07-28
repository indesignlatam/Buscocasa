<?php namespace App\Models;

use App\Models\IndesignModel;

class Appointment extends IndesignModel {
	//
    protected $softDelete = false;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'appointments';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

    /**
     * The rules to verify when creating.
     *
     * @var array
     */
    protected $rules        = [ 'user_id'       => 'numeric|exists:users,id',
                                'listing_id'    => 'required|numeric|exists:listings,id',
                                'name'          => 'required|string|max:255',
                                'email'         => 'required|email',
                                'phone'         => 'required|digits_between:7,15',
                                'comments'      => 'required|string|max:500',
                                ];

    /**
     * The rules to verify when editing.
     *
     * @var array
     */
    protected $editRules    = [ 'user_id'       => 'numeric|exists:users,id',
                                'listing_id'    => 'numeric|exists:listings,id',
                                'name'          => 'string|max:255',
                                'email'         => 'email',
                                'phone'         => 'string|max:15|min:7',
                                'comments'      => 'string|max:500',
                                ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'listing_id','name', 'email', 'phone', 'comments'];

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
    //protected $appends = ['image_url'];

    /**
     * The method that appends the attribute to JSON responces.
     *
     * @var null or attribute
     */
    // public function getImageUrlAttribute(){
    //  if($this->image_path){
    //      return asset($this->image_path);
    //  }
    //  return null;
    // }

    /**
     * Scope a query to only include non expired listings.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query){
        return $query->where('read', false);
    }

    public function scopeNotAnswered($query){
        return $query->where('answered', false);
    }



    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }

}
