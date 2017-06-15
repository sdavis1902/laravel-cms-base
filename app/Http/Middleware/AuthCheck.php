<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use App\Models\Site;
use Session;

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
		// they are logged in, lets check if they have 2fa enabled
		if($user->getVar('2fa_enabled') && !Session::has('2fa_confirmed')){
			return redirect()->guest('admin/auth/2fa');
		}
		\View::share('user', $user);

        return $next($request);
    }
}
