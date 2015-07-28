<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' 		=> 'required|string|max:255',
			'username' 	=> 'alpha_dash|max:255|unique:users',
			'phone' 	=> 'required|digits_between:7,15|unique:users,phone_1|unique:users,phone_2',
			'email' 	=> 'required|email|max:255|unique:users',
			'password' 	=> 'required|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'name' 					=> $data['name'],
			'username' 				=> md5($data['email']),
			'email' 				=> $data['email'],
			'phone_1' 				=> $data['phone'],
			'password' 				=> bcrypt($data['password']),
			'confirmation_code' 	=> str_random(64),
		]);
	}

}
