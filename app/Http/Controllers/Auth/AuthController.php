<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Socialize; 
use Queue;
use Analytics;
use Cookie;

use App\Models\Role;
use App\Models\Like;
use App\User;

use App\Jobs\SendUserConfirmationEmail;


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

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('recaptcha:false', ['only' => 'postRegister']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data){
        return Validator::make($data, [
            'name'      => 'required|max:255',
            'username'  => 'alpha_dash|max:255|unique:users',
            'phone'     => 'required|digits_between:7,15|unique:users,phone_1|unique:users,phone_2',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data){
        return User::create([
            'name'                  => $data['name'],
            'username'              => md5($data['email']),
            'email'                 => $data['email'],
            'phone_1'               => $data['phone'],
            'password'              => bcrypt($data['password']),
            'confirmation_code'     => str_random(64),
        ]);
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    // protected function getFailedLoginMessage(){
    //     return trans('auth.login_failed');
    // }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request){
        //
        $input              = $request->all();
        $input['phone']     = preg_replace("/[^0-9]/", "", $input['phone']);

        $validator = $this->validator($input);

        if($validator->fails()){
            $this->throwValidationException(
                $request, $validator
            );
        }

        // Create the user
        $user = $this->create($input);

        // Send confirmation email
        Queue::push(new SendUserConfirmationEmail($user));

        // Attach the registered.user role
        $role = Role::where('slug', 'registered.user')->first();
        $user->attachRole($role);

        // Login user
        Auth::login($user);

        // Push session var to know if user is new
        $request->session()->push('new_user', true);

        // Get liked listings and create them
        $cookie = null;
        if(Cookie::has('likes') && Cookie::get('likes') && count(Cookie::get('likes')) > 0){
            $likes = array_keys(Cookie::get('likes'));
            foreach ($likes as $like) {
                Like::create(['user_id'     => Auth::user()->id,
                              'listing_id'  => $like]);
            }
            $cookie = Cookie::forget('likes');
        }

        // Analytics event
        Analytics::trackEvent('User Registered', 'button', $user->id, 1);

        if($cookie){
            return redirect($this->redirectPath())->withCookie($cookie);
        }else{
            return redirect($this->redirectPath());
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request){

        $this->validate($request, [
            $this->loginUsername()  => 'required', 
            'password'              => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // Analytics event
            Analytics::trackEvent('User logged in', 'button', Auth::user()->id, 1);

            // Get liked listings and create them
            $cookie = null;
            if(Cookie::has('likes') && Cookie::get('likes') && count(Cookie::get('likes')) > 0){
                $likes = array_keys(Cookie::get('likes'));
                $liked = Like::whereIn('id', $likes)->get();
                $liked = array_pluck($liked, 'listing_id');

                foreach ($likes as $like) {
                    if(!in_array($like, $liked)){
                        Like::create(['user_id'     => Auth::user()->id,
                                      'listing_id'  => $like]);
                    }
                }
                $cookie = Cookie::forget('likes');
            }

            if($cookie){
                return $this->handleUserWasAuthenticated($request, $throttles)->withCookie($cookie);
            }

            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
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
            
            $user->name     = $providerUser->getName();
            $user->email    = $providerUser->getEmail();
            $user->confirmed= true;
            // $user->avatar        = $providerUser->getAvatar();
            // $user->user_id       = $providerUser->getId();

            $user->save();

            $role = Role::where('slug', '=', 'registered.user')->first();
            $user->attachRole($role);

            // Analytics event
            Analytics::trackEvent('User Registered by Facebook', 'button', $user->id, 1);
        }

        Auth::login($user);

        return redirect()->intended($this->redirectPath())->withSuccess(['Bienvenido ' . $user->name]);// TODO translate
    }

    protected function getFailedLoginMessage(){
        return trans('auth.login_failed');
    }
}
