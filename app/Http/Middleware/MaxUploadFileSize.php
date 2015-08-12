<?php namespace App\Http\Middleware;

use Closure;

class MaxUploadFileSize {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next){
		// Check if upload has exceeded max size limit
	    if (! ($request->isMethod('POST') or $request->isMethod('PUT')) ){ 
	    	return $next($request);
	    }
	    // Get the max upload size (in Mb, so convert it to bytes)
	    $post_size = 10;
	    if(ini_get('post_max_size') && ini_get('post_max_size') > 0){
	    	$post_size = ini_get('post_max_size');
	    }
	    $maxUploadSize 	= 1024 * 1024 * $post_size;
	    $contentSize 	= 0;
	    if (isset($_SERVER['HTTP_CONTENT_LENGTH'])){
	        $contentSize = $_SERVER['HTTP_CONTENT_LENGTH'];
	    }elseif (isset($_SERVER['CONTENT_LENGTH'])){
	        $contentSize = $_SERVER['CONTENT_LENGTH'];
	    }
	    // If content exceeds max size, throw an exception
	    if ($contentSize > $maxUploadSize){
	        return response()->json(['error' => trans('reponses.file_to_large')]);
	    }

		return $next($request);
	}

}