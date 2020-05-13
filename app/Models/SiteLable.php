<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */

namespace App\Models;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mockery\Exception;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as AuthUser;

//laravel伪删除类

class SiteLable extends BaseModel {
   protected $table ='site_lable';

    //获取盟友信息
    public function site3E21()
    {
        return $this->belongsTo('App\Models\Site3E21','siteid','siteid');
    }

   //查询数据
    public function get_list($params){
        //查询
        $query = $this;
        $query = $query->where('isdel',0);
        if (!empty($params['stype'])) {
            $query = $query->where('type',$params['stype']);
        }
        $query = $query->where('siteid',$params['siteid']);
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->orderBy('id', 'desc')->paginate($limit);
        $appendData = $re->appends(array(
            'stype' => @$params['stype'],
            'limit' => @$params['limit'],
            'siteid' => @$params['siteid'],
        ));
        return $re;
    }

    //添加二级域名
    public function level_add($params){
        $params['created_at'] = getDateTime();
        $insert = $this->insert($params);
        if($insert){
            return ['code' => 200, 'infor' => '添加成功'];
        }else{
            return ['code' => 456, 'infor' => '网络异常,添加失败'];
        }
    }


    //修改
    public function edit($params)
    {
        try {
            $id=$params['id'];
            unset($params['id']);
            $update = $this->where('id', $id)->update($params);
            if ($update) {
                return ['code' => 200, 'infor' => '修改成功'];
            } else {
                return ['code' => 456, 'infor' => '无数据变更'];
            }
        }catch (\Exception $e){
            return ['code' => 500, 'infor' => $e->getMessage()];
        }
    }



}
