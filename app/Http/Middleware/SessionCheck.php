<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Base\Helpers\SessionActivityLog;

class SessionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $active_sessions = DB::connection('pgsql2')
        ->table('session_log')
        ->whereNull('logout_time')
        ->get();

        foreach($active_sessions as $session)
        {
            if(request()->session()->get('sessionId') != $session->id)
            {
                $time = date("h:i:sa");
                $time = SessionActivityLog::englishToNepali($time);
                
                DB::connection('pgsql2')
                ->table('session_log')
                ->where('id',$session->id)
                ->update(['is_currently_logged_in' => false, 'logout_time' => $time]);
            }
        }
        return $next($request);
    }
}
