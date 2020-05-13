<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Validate;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

class SiteLevelValidate
{
    public $customAttributes = [
        'siteid'=>'站点ID',
        'field_label'=>'标识',
        'top_label'=>'顶级域名',
        'type'=>'商品',
    ];

    //添加验证
    public static function LevelCreate($params, &$msg)
    {
        $rules = [
            'siteid'=>'required',
            'field_label'=>'required|max:50',
            'top_label' => 'required|max:50',
            'type'=>'required',
        ];

        $message = [
            'siteid.required' => '缺少关键参数',
            'field_label.required' => '请填写标识',
            'top_label.required' => '请填写二级域名',
            'type.required' => '请选择商品',
        ];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        //手动验证，每个类型只能有一个配置
        $one=DB::table('site_lable')
            ->where('siteid',$params['siteid'])
            ->where('type',$params['type'])
            ->where('isdel',0)
            ->first();
        if ($one){
            $msg = ['code'=>456, 'infor'=>'该系统已存在配置'];
            return false;
        }


        return true;
    }

    //添加验证
    public static function LevelUpdate($params, &$msg)
    {
        $rules = [
            'id'=>'required',
            'field_label'=>'required|max:50',
            'top_label' => 'required|max:50',
            'type'=>'required',
        ];

        $message = [
            'id.required' => '缺少关键参数',
            'field_label.required' => '请填写标识',
            'top_label.required' => '请填写二级域名',
            'type.required' => '请选择商品',
        ];

        $customAttributes = (new self())->customAttributes;

        $validator = Validator::make($params, $rules, $message,$customAttributes);
        if ($validator->fails()) {
            $msg = ['code'=>456, 'infor'=>$validator->errors()->first()];
            return false;
        }
        return true;
    }

    //站点删除
    public static function dataDelete($params, &$msg)
    {
        // 规则
        $rules = [
            'id'=>['required',Rule::exists('site_lable')->where(function($query){
                $query->where('isdel',0);
            }),],
        ];
        $messages = [
            'id.exists'=>'域名不存在',
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




}