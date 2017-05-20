<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use App\Models\Site;

class AuthCheck
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
        if( !Sentinel::check() ){
            if( $request->ajax() ){
                return response()->json('NOT LOGGED IN');
            }
            return redirect()->guest('admin/auth/login');
        }

		$user = Sentinel::getUser();
		\View::share('user', $user);

        return $next($request);
    }
}
