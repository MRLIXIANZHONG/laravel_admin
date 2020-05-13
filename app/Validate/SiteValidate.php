<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Validate;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class SiteValidate
{
    public $customAttributes = [
        'siteid'=>'站点ID',
        'site_name'=>'站点名称',
        'companyno'=>'代理公司',
        'weburl'=>'网址',
        'web_dir'=>'站点文件夹',
        'city_type'=>'站点城市类型',
        'sfid'=>'省',
        'dsid'=>'市',
        'areano'=>'区县',
        'areatitle'=>'城市名',
        'logo'=>'logo',
        'logotxt'=>'简介文本',
        'memo'=>'备注文本',
        'banner'=>'banner',
        'title'=>'网站标题',
        'jftime'=>'加盟时间',
        'jfendtime'=>'加盟到期时间',
        'addUsername'=>'添加人',
        'qq'=>'工作QQ',
        'linkmemo'=>'联系备注',
        'spell'=>'站点首字母拼音',
        'initial'=>'站点全拼',
    ];

    //添加验证
    public static function SiteCreate($params, &$msg)
    {
        $rules = [
            'site_name'=>'required|max:30',
            'companyno' => ['required','integer',Rule::exists('companys','id')->where(function ($query){
                $query->whereNull('deleted_at');
            }),],
            'weburl'=>'required|max:50',
            'web_dir' => 'required|max:50',
            'city_type'=>'required|max:30',
            'sfid' => ['required','integer',Rule::exists('area3e21','areaid')],
            'dsid' => ['required','integer',Rule::exists('area3e21','areaid')],
            'areano' => ['required','integer',Rule::exists('area3e21','areaid')],
            'areatitle'=>'required|max:30',
            'logo'=>'required|max:50',
            'logotxt'=>'required|max:50',
            'memo'=>'max:200',
            'title'=>'required|max:100',
            'jftime'=>'required|date',
            'jfendtime'=>'required|date',
            //'addtime'=>'required|date',
            'addUsername'=>'max:30',
            'qq'=>'max:100',
            'linkmemo'=>'max:2000',
            'spell'=>'required|max:50',
            'initial'=>'required|max:20',
            //'lastLoginTime'=>'present|date',
            //'yingshou'=>'required|numeric|max:999999999999',
            //'shishou'=>'required|numeric|max:999999999999',
            //'xinyong'=>'required|integer|max:99999999999',
        ];

        $message = [
            'companyno.exists' => '不存在的公司',
            'sfid.exists' => '不存在的省',
            'dsid.exists' => '不存在的市',
            'areano.exists' => '不存在的区县',
        ];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证
        if(getAuth()->siteid !== User::SUPERADMIN){
            $msg = ['code'=>456, 'infor'=>'您无权添加站点配置信息'];
            return false;
        }
        if($params['jftime'] > $params['jfendtime']){
            $msg = ['code'=>456, 'infor'=>'加盟到期时间不能小于加盟时间'];
            return false;
        }
        return true;
    }
    //更新验证
    public static function SiteUpdate($params, &$msg)
    {
        $rules = [
            'siteid' => ['required','integer',Rule::exists('site_3e21','siteid')->where(function($query){
                $query->where('isdel',0);
            })],
            'site_name'=>'required|max:30',
            'companyno' => ['required','integer',Rule::exists('companys','id')->where(function ($query){
                $query->whereNull('deleted_at');
            }),],
            'weburl'=>'required|max:50',
            'web_dir' => 'required|max:50',
            'city_type'=>'required|max:30',
            'sfid' => ['required','integer',Rule::exists('area3e21','areaid')],
            'dsid' => ['required','integer',Rule::exists('area3e21','areaid')],
            'areano' => ['required','integer',Rule::exists('area3e21','areaid')],
            'areatitle'=>'required|max:30',
            'logo'=>'required|max:50',
            'logotxt'=>'required|max:50',
            'memo'=>'max:200',
            'title'=>'required|max:100',
            'jftime'=>'required|date',
            'jfendtime'=>'required|date',
            'addUsername'=>'max:30',
            'qq'=>'max:100',
            'linkmemo'=>'max:2000',
            'spell'=>'required|max:50',
            'initial'=>'required|max:20',
        ];

        $message = [
            'siteid.exists'=>'该站点不存在,请刷新页面后再次进行操作',
            'companyno.exists' => '不存在的公司',
            'sfid.exists' => '不存在的省',
            'dsid.exists' => '不存在的市',
            'areano.exists' => '不存在的区县',
        ];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证|修改为普通管理也可以修改站点信息[部分基础展示型字段可改->具体字段查看相关MODEL具体操作方法]
//        if(getAuth()->siteid !== User::SUPERADMIN){
//            $msg = ['code'=>456, 'infor'=>'您无权修改站点配置信息'];
//            return false;
//        }
        if($params['jftime'] > $params['jfendtime']){
            $msg = ['code'=>456, 'infor'=>'加盟到期时间不能小于加盟时间'];
            return false;
        }
        return true;
    }

    //站点删除
    public static function dataDelete($params, &$msg)
    {
        // 规则
        $rules = [
            'siteid'=>['required',Rule::exists('site_3e21')->where(function($query){
                $query->where('isdel',0);
            }),],
        ];
        $messages = [
            'siteid.exists'=>'站点不存在',
        ];
        $customAttributes = (new self())->customAttributes;
        // 验证
        $validator = Validator::make($params, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        return true;
    }

    //微信用户绑定
    public static function WechatBind($params, &$msg)
    {
        if(!@$params['uid'] || !@$params['openid'] || !@$params['nickname'] || !@$params['avatar_wechat']){
            $msg = ['code'=>456, 'infor'=>'参数缺少'];return false;
        }
        if(!User::find($params['uid'])){
            $msg = ['code'=>456, 'infor'=>'不存在的用户'];return false;
        }
        if(User::where('openid',$params['openid'])->first()){
            $msg = ['code'=>456, 'infor'=>'openid已存在'];return false;
        }
        return true;
    }



}