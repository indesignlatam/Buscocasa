<?php namespace App\Http\Middleware;

use Closure, Redirect;
use GrahamCampbell\Throttle\Throttle;
use Illuminate\Contracts\Routing\Middleware;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class AuthThrottleMiddleware {

	/**
     * The throttle instance.
     *
     * @var \GrahamCampbell\Throttle\Throttle
     */
    protected $throttle;

    /**
     * Create a new instance.
     *
     * @param \GrahamCampbell\Throttle\Throttle $throttle
     *
     * @return void
     */
    public function __construct(Throttle $throttle){
        $this->throttle = $throttle;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $limit = 10, $time = 3){
        if(env('APP_ENV') == 'production'){
            if (!$this->throttle->attempt($request, $limit, $time)) {
                return Redirect::back()
                        ->withInput($request->all())
                        ->withErrors([
                            'rate_limit_exeeded' => trans('responses.rate_limit_exeeded'),
                    ]);
            }
        }

        return $next($request);
    }
}