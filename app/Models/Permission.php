<?php namespace App\Models;

use Bican\Roles\Models\Permission as BicanPermission;

use Validator;

class Permission extends BicanPermission {
    
    /**
     * The rules to verify when creating.
     *
     * @var array
     */
	protected $rules 		= [ 'name' 			=> 'required|string|max:255',
								'slug' 			=> 'required|string|max:255',
								'description' 	=> 'required|string|max:255',
								'model' 		=> 'string|max:255'
								];

    protected $editRules 	= [ 'name' 			=> 'string|max:255',
								'slug' 			=> 'string|max:255',
								'description' 	=> 'string|max:255',
								'model' 		=> 'string|max:255'
								];

    /**
     * The attributes that are hidden to JSON responces.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name', 
                            'slug', 
                            'description', 
                            'model',
                            ];



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