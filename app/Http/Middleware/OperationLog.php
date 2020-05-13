<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Middleware;

use Agent;
use Closure;
use Illuminate\Http\Request;
use Zhuzhichao\IpLocationZh\Ip;
use App\Models\SystemLog;

class OperationLog
{
    public function handle(Request $request, Closure $next)
    {
        //if (app()->environment('production')) {//线上将APP_ENV改为production并开启该判断
        if(!$request->isMethod('GET')) {
            $userArr = getAuth();
            $systemLog = new SystemLog;
            $systemLog->user_id = $userArr ? $userArr->id : 0;
            $systemLog->path = $request->path();
            $systemLog->method = $request->method();
            $systemLog->agent = Agent::getUserAgent();
            $systemLog->ip = $request->getClientIp();
            $systemLog->sql = '';
            $systemLog->ip_info = @join(array_unique(array_filter(Ip::find($request->getClientIp()))), ' ');
            $systemLog->params = http_build_query($request->except('_method', '_token'));
            $systemLog->save();
        }
        //}

        return $next($request);
    }
}
