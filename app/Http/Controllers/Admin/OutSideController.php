<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 * Date: 2019/10/20
 */
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\SendEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OutSideController extends BaseController {

    /**
     * 登录
     * Created by Lxd
     * @param Request $request
     * @return bool|\Illuminate\Contracts\View\Factory
     */
    public function adminlogin(Request $request){
        if($request->isMethod('put')){
            $parms = $request->except('_token');
            $login = (new User())->UserLogin($parms);
            return $login;
        }
        if(Auth::guard('admin_user')->check())
            return redirect('/admin');
        else
            return view('Admin.outside.adminlogin');
    }

    /**
     * 退出登录
     * Created by Lxd
     * @return \Illuminate\Http\JsonResponse
     */
    public function quit(){
        (new User)->UserLoginOut();
        return response()->json(['code'=>200,'infor'=>'退出成功,即将跳转登录页']);
    }

    /**
     * 退出登录
     * Created by Lxd
     * @return \Illuminate\Http\RedirectResponse
     */
    public function directQuit(){
        (new User)->UserLoginOut();
        return redirect('admin/login');
    }

    /**
     * 自定义信息抛出
     * Created by Lxd
     * @param null $eror
     * @return \Illuminate\Contracts\View\Factory
     */
    public function Custom_throw(Request $request){
        $eror = $request->get('error');
        if($eror == null){
            $eror = "服务器开小差了~~";
        }
        return view("errors.Custom_throw",compact('eror'));
    }

    /**
     * 重置密码邮件发送
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function resetPwd(Request $request){
        if($request->isMethod('put')){
            $email = $request->input(['email']);
            if($userInfo = (new User)->getUserInfoByEmail($email)){
                $execute = (new SendEmail())->sendResetPwd($userInfo);
                return $execute;
            }
            return response()->json(['code'=>302,'infor'=>'你输入的邮箱系统内尚未注册']);
        }
        return view('Admin.outside.resetPwd');
    }

    /**
     * 重置密码执行
     * Created by Lxd
     * @param Request $request
     * @param null $token
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function executeReserPwd(Request $request){
        if($request->isMethod('put')){
            $parms = $request->input();
            $execute = (new User())->resetPwd($parms);
            return $execute;
        }
        $token = $request->get('token');
        $email = (new User)->checkResetPwdToken($token);
        if(is_object($email)) return $email;
        return view('Admin.outside.executeReserPwd',compact('email'));
    }
}
