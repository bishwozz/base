<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Redirect;
use Closure;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $url = str_replace($request->url(), "", $request->fullUrl());
        $input = $request->all();
        // dd($request->url(),$request->fullUrl(),$request,$url);

        array_walk_recursive($input, function (&$input) {
            $input = strip_tags($input);
        });

        // & removed from below preg_match it was '/[\'^£$%&*()}{@#~><>|+¬]/' before
        if (preg_match('/[\'^£$%*()}{@#~><>|+¬]/', $request->fullUrl()))
            abort(403);
            
        $request->merge($input);
        return $next($request);
    }
}
