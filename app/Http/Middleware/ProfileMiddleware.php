<?php
/**
 * @Notes:
 * @Date: 2026/1/16
 * @Time: 10:33
 * @Interface ProfileMiddleware
 * @return
 */

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Closure;

/**
 * User: qinfuxing
 * Date: 2026/1/16
 * Time: 10:33
 */
class ProfileMiddleware
{
    /**
     * 处理传入的请求。
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->route()?->named('profile.index')) {

        }

        return $next($request);
    }
}
