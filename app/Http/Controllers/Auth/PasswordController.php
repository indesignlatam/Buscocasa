<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Http\Request;

class PasswordController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

	/**
	 * Create a new password controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
	 * @return void
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords){
		$this->auth = $auth;
		$this->passwords = $passwords;
		// Translate the password reset email subject
		$this->subject = trans('emails.password_reset_subject');
		$this->middleware('guest');
		$this->middleware('throttle', ['only' => ['postEmail']]);
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function postEmail(Request $request){
		if(!$request->has('g-recaptcha-response')){
			return redirect('/password/email')->withErrors([trans('auth.recaptcha_error')])->withInput();
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
			return redirect('/password/email')->withErrors([trans('auth.youre_bot')])->withInput();
		}


		$this->validate($request, ['email' => 'required|email']);

		$response = $this->passwords->sendResetLink($request->only('email'), function($m){
			$m->subject($this->getEmailSubject());
		});

		switch ($response){
			case PasswordBroker::RESET_LINK_SENT:
				return redirect()->back()->with('status', trans($response));

			case PasswordBroker::INVALID_USER:
				return redirect()->back()->withErrors(['email' => trans($response)]);
		}
	}

}
