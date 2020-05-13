<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Validate;

use App\Models\Tpay;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TpayValidate
{
    public $customAttributes = [
        'id'=>'ID',
        'compname'=>'公司名称',
        'isali'=>'支付宝PC扫码支付',
        'alipay1'=>'支付宝PC扫码账号',
        'alipay2'=>'支付宝PC扫码appid',
        'alipay3'=>'支付宝PC扫码商户支付密钥',
        'isaliphone'=>'支付宝手机网页支付',
        'alipayphone1'=>'支付宝手机网页支付账号',
        'alipayphone2'=>'支付宝手机网页支付appid',
        'alipayphone3'=>'支付宝手机网页支付密钥',
        'IsAlipayApp'=>'支付宝APP支付',
        'AlipayApp1'=>'支付宝APP支付账号',
        'AlipayApp2'=>'支付宝APP支付appid',
        'AlipayApp3'=>'支付宝APP支付私钥',
        'isWx'=>'微信支付',
        'wxAppID'=>'微信公众平台appid',
        'wxAppSecret'=>'微信公众平台secret',
        'wxMchID'=>'商户平台商户号',
        'wxKey'=>'商户平台商户key',
    ];

    //添加验证
    public static function BankCreate($params, &$msg)
    {
        $rules = [
            'compname'=>'required|max:500',
            'siteid' => ['required','integer',Rule::exists('site_3e21','siteid')->where(function ($query){
                $query->where('isdel',0);
            }),Rule::unique('t_pay','siteid')->where(function ($query){
                $query->where('isdel',0);
            })],
            'isali'=>'integer|in:'.implode(',', array_keys((new Tpay())->isaliLabel)),
            'alipay1'=>'required|max:500',
            'alipay2'=>'required|max:500',
            'alipay3'=>'required|max:500',
            'isaliphone'=>'integer|in:'.implode(',', array_keys((new Tpay())->isaliphoneLabel)),
            'alipayphone1'=>'required|max:500',
            'alipayphone2'=>'required|max:500',
            'alipayphone3'=>'required|max:500',
            'IsAlipayApp'=>'integer|in:'.implode(',', array_keys((new Tpay())->IsAlipayAppLabel)),
            'AlipayApp1'=>'required|max:50',
            'AlipayApp2'=>'required|max:50',
            'AlipayApp3'=>'required|max:999999',
            'isWx'=>'integer|in:'.implode(',', array_keys((new Tpay())->isWxLabel)),
            'wxAppID'=>'max:50',
            'wxAppSecret'=>'max:50',
            'wxMchID'=>'max:50',
            'wxKey'=>'max:50',
        ];

        $message = [
            'siteid.exists' => '不存在的站点',
            'siteid.unique' => '该站点已配置过支付数据,不可新增,只能修改',
            'isali.in'=>'支付宝PC扫码仅可选择开启或关闭',
            'isaliphone.in'=>'支付宝手机网页支付仅可选择开启或关闭',
            'IsAlipayApp.in'=>'支付宝APP支付仅可选择开启或关闭',
            'isWx.in'=>'微信支付仅可选择开启或关闭',
        ];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证
        if(getAuth()->siteid !== User::SUPERADMIN){
            $msg = ['code'=>456, 'infor'=>'您无权添加站点支付配置数据'];
            return false;
        }
        return true;
    }

    //更新验证
    public static function BankUpdate($params, &$msg)
    {
        $rules = [
            'id' => ['required','integer',Rule::exists('t_pay','id')->where(function($query){
                $query->where('isdel',0);
            })],
            'compname'=>'required|max:500',
            'isali'=>'integer|in:'.implode(',', array_keys((new Tpay())->isaliLabel)),
            'alipay1'=>'required|max:500',
            'alipay2'=>'required|max:500',
            'alipay3'=>'required|max:500',
            'isaliphone'=>'integer|in:'.implode(',', array_keys((new Tpay())->isaliphoneLabel)),
            'alipayphone1'=>'required|max:500',
            'alipayphone2'=>'required|max:500',
            'alipayphone3'=>'required|max:500',
            'IsAlipayApp'=>'integer|in:'.implode(',', array_keys((new Tpay())->IsAlipayAppLabel)),
            'AlipayApp1'=>'required|max:50',
            'AlipayApp2'=>'required|max:50',
            'AlipayApp3'=>'required|max:999999',
            'isWx'=>'integer|in:'.implode(',', array_keys((new Tpay())->isWxLabel)),
            'wxAppID'=>'max:50',
            'wxAppSecret'=>'max:50',
            'wxMchID'=>'max:50',
            'wxKey'=>'max:50',
        ];

        $message = [
            'id.exists'=>'该配置不存在,请刷新页面后再次进行操作',
            'isali.in'=>'支付宝PC扫码仅可选择开启或关闭',
            'isaliphone.in'=>'支付宝手机网页支付仅可选择开启或关闭',
            'IsAlipayApp.in'=>'支付宝APP支付仅可选择开启或关闭',
            'isWx.in'=>'微信支付仅可选择开启或关闭',
        ];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证
        if(getAuth()->siteid !== User::SUPERADMIN && getAuth()->siteid !== Tpay::find($params['id'])->siteid){
            $msg = ['code'=>456, 'infor'=>'您无权修改非您公司下的商户支付配置'];
            return false;
        }
        return true;
    }
}