<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                      => \App\Http\Middleware\Authenticate::class,
        'auth.basic'                => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'                     => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'auth.admin'                => \App\Http\Middleware\IsAdmin::class,
        'recaptcha'                 => \App\Http\Middleware\ReCaptcha::class,
        'listings.view.throttle'    => \App\Http\Middleware\ListingViewThrottle::class,
        'throttle'                  => \App\Http\Middleware\ThrottleMiddleware::class,
        'throttle.auth'             => \App\Http\Middleware\AuthThrottleMiddleware::class,
        'file_max_upload_size'      => \App\Http\Middleware\MaxUploadFileSize::class,
    ];
}
