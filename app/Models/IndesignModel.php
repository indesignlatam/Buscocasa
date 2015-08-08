<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

use Validator;

class IndesignModel extends Model {

    use Rememberable;

    /**
     * The attributes that would be returned as dates
     *
     * @var string
     */
    protected $dates = ['created_at', 'update_at', 'deleted_at'];

    /**
     * The attributes that are hidden to JSON responces.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'deleted_at'];
    

	protected $rules = [];
    protected $editRules = [];

    protected $errors;

    public function validate($data, $rulesAdd = null, $isEdit = false){

        $rules = $this->rules;

        if($isEdit){
            $rules = $this->editRules;
        }

    	if($rulesAdd){
    		array_merge($rules,$rulesAdd);
    	}
    	
        // make a new validator object
        $validator = Validator::make($data, $rules);

        // check for failure
        if ($validator->fails()){
            // set errors and return false
            $this->errors = $validator->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors(){
        return $this->errors;
    }

}