<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language
{
    public function handle($request, Closure $next)
    {
        if(backpack_user()){
            if(backpack_user()->uis()->first()){
                if (backpack_user()->uis()->first()->lang) {
                    App::setLocale(backpack_user()->uis()->first()->lang);
                }else { // This is optional as Laravel will automatically set the fallback language if there is none specified
                    App::setLocale(Config::get('app.fallback_locale'));
                }
            }else { // This is optional as Laravel will automatically set the fallback language if there is none specified
                App::setLocale(Config::get('app.fallback_locale'));
            }
        }else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(Config::get('app.fallback_locale'));
        }
        return $next($request);
    }
}