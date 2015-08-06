<?php namespace App\Http\Middleware;

use Closure, Redirect;
use GrahamCampbell\Throttle\Throttle;
use Illuminate\Contracts\Routing\Middleware;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ThrottleMiddleware {

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
    public function handle($request, Closure $next){
        $limit 	= 1; // request limit
        $time 	= 1; // ban time

        if (!$this->throttle->attempt($request, $limit, $time)) {
            return Redirect::back()
                    ->withInput($request->all())
                    ->withErrors([
                        'rate_limit_exeeded' => 'Ha enviado muchos mensajes en muy poco tiempo! Espera un momento para volver a enviar mas mensajes.',
                ]);
            //throw new TooManyRequestsHttpException($time * 60, 'Rate limit exceed.');
        }

        return $next($request);
    }
}