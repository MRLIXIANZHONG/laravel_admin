<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;

use Illuminate\Support\Facades\Mail;

class SendEmail extends BaseModel{

    /**
     * 发送重置密码邮件
     * Created by Lxd
     * @param $userInfo
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetPwd($userInfo){
        $token = (new User)->getResetPwdToken($userInfo);
        $res = ['email'=>$userInfo['email'],'name'=>"丢失密码的用户",'token'=>$token];
        Mail::send('emails.resetpwd',['res'=>$res],function($message)use($res) {
            $message ->to($res['email'])->subject('平台密码修改验证');
        });
        if(count(Mail::failures()) < 1){
            return response()->json(['code'=>200,'infor'=>'发送邮件成功，请查收！']);
        }else{
            return response()->json(['code'=>302,'infor'=>'发送邮件失败，请重试！']);
        }
    }

}
