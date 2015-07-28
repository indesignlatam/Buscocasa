<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

use Validator;

class IndesignModel extends Model {

    use Rememberable;

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
