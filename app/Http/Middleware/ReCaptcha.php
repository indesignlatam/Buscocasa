<?php namespace App\Http\Middleware;

use Closure;
use Auth;

class ReCaptcha{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $checkAuth = true){
        if(!Auth::check() && $checkAuth){
            if(!$request->has('g-recaptcha-response')){
                return redirect()->back()->withErrors([trans('auth.recaptcha_error')])->withInput();
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 
                     http_build_query([ 'secret'    => '6Ldv5wgTAAAAAKsrEHnUTD2wKdUtrfQUxFo_S3lq',
                                        'response'  => $request->get('g-recaptcha-response'),
                                        'remoteip'  => $request->getClientIp()
                                        ]));

            // receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $captcha = curl_exec($ch);
            $captcha = json_decode($captcha, true);
            curl_close ($ch);

            if(!$captcha['success']){
                return redirect()->back()->withErrors([trans('auth.youre_bot')])->withInput();
            }
        }

        return $next($request);
    }
}
