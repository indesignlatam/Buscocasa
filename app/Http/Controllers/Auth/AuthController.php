<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;

use App\Commands\SendUserConfirmationEmail;
use Auth;
use Socialize; 
use Queue;
use Analytics;
use	App\Models\Role;
use App\User; 

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar){
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
		$this->middleware('throttle.auth', ['only' => ['postLogin']]);
	}

	/**
	 * Get the failed login message.
	 *
	 * @return string
	 */
	protected function getFailedLoginMessage(){
		return trans('auth.login_failed');
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postRegister(Request $request){

		if(!$request->has('g-recaptcha-response')){
			return redirect('/auth/register')->withErrors([trans('auth.recaptcha_error')])->withInput();
		}

		// Captcha verify
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
		         http_build_query(['secret' 	=> '6Ldv5wgTAAAAAKsrEHnUTD2wKdUtrfQUxFo_S3lq',
		         					'response' 	=> $request->get('g-recaptcha-response'),
		         					'remoteip'	=> $request->getClientIp()
		         					]));

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$captcha = curl_exec($ch);
		$captcha = json_decode($captcha, true);
		curl_close ($ch);
		// Captcha verify

		if(!$captcha['success']){
			return redirect('/auth/register')->withErrors([trans('auth.youre_bot')])->withInput();
		}

		$input 				= $request->all();
		$input['phone'] 	= preg_replace("/[^0-9]/", "", $input['phone']);

		$validator = $this->registrar->validator($input);

		if ($validator->fails()){
			$this->throwValidationException(
				$request, $validator
			);
		}

		$user = $this->registrar->create($input);
		Queue::push(new SendUserConfirmationEmail($user));
		$this->auth->login($user);

		$role = Role::where('slug', 'registered.user')->first();
		$user->attachRole($role);

		$request->session()->push('new_user', true);

		// Analytics event
		Analytics::trackEvent('User Registered', 'button', $user->id);

		return redirect($this->redirectPath());
	}

	public function postLogin(Request $request){

		$this->validate($request, [
			'email' 	=> 'required|string|min:6', 
			'password' 	=> 'required|min:6|string',
		]);

		$credentials = $request->only('email', 'password');

		if ($this->auth->attempt($credentials, $request->has('remember'))){
			// Analytics event
			Analytics::trackEvent('User Logged In', 'button', Auth::user()->id);

			return redirect()->intended($this->redirectPath());
		}else if($this->auth->attempt(['email'=> $request->username, 'password' => $request->password], $request->has('remember'))) {
			// Analytics event
			Analytics::trackEvent('User Logged In', 'button', Auth::user()->id);

		    return redirect()->intended($this->redirectPath());
		}

		// Analytics event
		Analytics::trackEvent('Error in login', 'button', $request->has('email'));

		return redirect($this->loginPath())
					->withInput($request->only('email', 'remember'))
					->withErrors([
						'username' => $this->getFailedLoginMessage(),
					]);
	}

	private function redirectPath(){
		if(Auth::user()->is('admin')){
			return '/admin';
		}else{
			if(count(Auth::user()->listings) > 0){
				return '/admin';
			}
			return '/admin/listings/create';
		}
	}


	public function redirectToProvider($provider = null){
		if(!$provider || $provider != 'facebook'){
			abort(404);
		}

	    return Socialize::with($provider)->redirect();
	}

	public function handleProviderCallback($provider = null){
		if(!$provider || $provider != 'facebook'){
			abort(404);
		}

	    $providerUser = Socialize::with($provider)->user();

	    // OAuth Two Providers
		$token = $providerUser->token;

		$user = User::firstOrNew(['email' => $providerUser->getEmail()]);
		if(!$user->id){
			if($providerUser->getNickname()){
				$user->username = md5($providerUser->getEmail());
			}
			
			$user->name 	= $providerUser->getName();
			$user->email 	= $providerUser->getEmail();
			$user->confirmed= true;
			// $user->avatar 		= $providerUser->getAvatar();
			// $user->user_id 		= $providerUser->getId();

			$user->save();

			$role = Role::where('slug', '=', 'registered.user')->first();
			$user->attachRole($role);

			// Analytics event
			Analytics::trackEvent('User Registered by Facebook', 'button', $user->id);
		}

		Auth::login($user);

		return redirect()->intended($this->redirectPath())->withSuccess(['Bienvenido ' . $user->name]);// TODO translate
	}

}