<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use App\Models\Folder;

class GetFrontMenuItems {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$folders = Folder::with('pages')->where('status', '=', 'Active')
			->where('visible', '=', 'Yes')->orderBy('order', 'asc')->get();

		\View::share('folders', $folders);

        return $next($request);
    }
}
