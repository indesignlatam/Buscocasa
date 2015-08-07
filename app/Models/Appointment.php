<?php namespace App\Models;

use App\Models\IndesignModel;

class Appointment extends IndesignModel {

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'appointments';

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
                                'listing_id'    => 'required|numeric|exists:listings,id',
                                'name'          => 'required|string|max:255',
                                'email'         => 'required|email',
                                'phone'         => 'required|digits_between:7,15',
                                'comments'      => 'required|string|max:500',
                                ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'user_id', 
                            'listing_id', 
                            'name', 
                            'email', 
                            'phone', 
                            'comments',
                            ];


    /**
     * Scope a query to only include unread messages
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query){
        return $query->where('read', false);
    }

    /**
     * Scope a query to only include unanswered messages
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAnswered($query){
        return $query->where('answered', false);
    }


    /**
     * Relationship with user to whom the message belongs
     *
     * @return \App\User
     */
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Relationship with listing which the message belongs to
     *
     * @return \App\Models\Listing
     */
    public function listing(){
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }

}