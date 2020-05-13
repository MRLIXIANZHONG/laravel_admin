<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Validate;

use App\Models\Site3E21;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyValidate
{
    public $customAttributes = [
        'id'=>'ID',
        'name'=>'公司名',
        'add'=>'公司地址',
        'liaison'=>'联系人',
        'liaison_phone'=>'联系人电话',
        'remark'=>'备注',
    ];

    //添加验证
    public static function dataCreate($params, &$msg)
    {
        $rules = [
            'name'=>'required|max:255',
            'add'=>'required|max:255',
            'liaison' => 'required|max:32',
            'liaison_phone'=>'required|max:255',
            'remark'=>'max:999999',
        ];

        $message = [];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证
        if(getAuth()->siteid !== User::SUPERADMIN){
            $msg = ['code'=>456, 'infor'=>'您无权添加公司'];
            return false;
        }
        return true;
    }

    //更新验证
    public static function dataUpdate($params, &$msg)
    {
        $rules = [
            'name'=>'required|max:255',
            'add'=>'required|max:255',
            'liaison' => 'required|max:32',
            'liaison_phone'=>'required|max:255',
            'remark'=>'max:999999',
        ];

        $message = [];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证
        if(getAuth()->siteid !== User::SUPERADMIN){
            $msg = ['code'=>456, 'infor'=>'您无权修改公司'];
            return false;
        }
        return true;
    }

    //删除验证
    public static function dataDelete($params, &$msg)
    {
        // 规则
        $rules = [
            'id'=>['required',Rule::exists('companys')->where(function($query){
                $query->whereNull('deleted_at');
            }),],
        ];
        $messages = [
            'id.exists'=>'公司不存在',
        ];
        $customAttributes = (new self())->customAttributes;
        // 验证
        $validator = Validator::make($params, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }

        // 判断公司下是否有站点
        $site = Site3E21::where('companyno',$params['id'])->value('siteid');
        if ($site) {
            $msg = ['code'=>456, 'infor'=>'该公司有站点数据绑定,不可删除'];
            return false;
        }
        return true;
    }
}