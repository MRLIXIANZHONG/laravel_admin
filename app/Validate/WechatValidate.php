<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Validate;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WechatValidate
{
    public static function WechatUpdate($params, &$msg)
    {
        $rules = [
            'app_id'=>'required|max:500',
            'secret'=>'required|max:500',
            'token' => 'required|max:999999',
            'aes_key'=>'max:999999',
        ];

        $message = [];

        $customAttributes = [
            'app_id'=>'微信app_id',
            'secret'=>'微信secret',
            'token'=>'微信token',
            'aes_key'=>'微信aes_key',
        ];

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证
        if(getAuth()->siteid !== User::SUPERADMIN){
            $msg = ['code'=>456, 'infor'=>'您无权修改该配置'];
            return false;
        }
        return true;
    }
}