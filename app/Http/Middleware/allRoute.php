<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Middleware;

use Closure;

class allRoute
{
    /**
     * Created by Lxd
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
