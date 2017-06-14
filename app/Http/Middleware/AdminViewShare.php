<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use App\Models\Project;
use View;

class AdminViewShare
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
		$current_url = $request->path();
		View::share('current_url', $current_url);

		View::share('app_title', env('APP_TITLE'));
        View::share('app_title_short', env('APP_TITLE_SHORT'));

        return $next($request);
    }
}
