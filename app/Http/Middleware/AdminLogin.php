<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Middleware;

use Closure;
use App\Models\Iprecording;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminLogin{
    /**
     * 验证登陆的请求
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * $request->path() 请求路由
     * $request->getClientIp(); 请求IP
     */
    public function handle($request, Closure $next)
    {
        //dd(Auth::check());
        if(!Auth::guard('admin_user')->check()){
            return redirect('admin/login');
        }
        if(!getAuth()->can($request->path())){
            if($request->ajax())
                return response()->json(['code'=>401,'infor'=>'你没有权限进行该操作']);
            else
                abort(401,'你没有权限进行该操作');
        }
        if(getAuth()->type == User::TYPEDISABLE){
            if($request->ajax())
                return response()->json(['code'=>401,'infor'=>'您的账号已被后台禁用']);
            else
                abort(401,'您的账号已被后台禁用');
        }
        $re = (new Iprecording())->user_ip_routepath_up($request->getClientIp(),$request->path(),2);
        if($re == Iprecording::IPTYPEDISABLE){
            $eror = '我也不知道为什么,但是你的IP确实已经被后台锁死了~';
            return redirect('Custom_throw?error=' . $eror);
        }
        return $next($request);
    }
}
