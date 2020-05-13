<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


class SystemLog extends BaseModel
{
    protected $table = 'systemlogs';

    //获取用户信息
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * model检索
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        $query = $this;
        if (!empty($params['date1']) && !empty($params['date2'])){
            $data1 = date('Y-m-d H:i:s',$params['date1']);
            $data2 = date('Y-m-d H:i:s',$params['date2']);
            $query = $query->where('created_at','>=',$data1)->where('created_at','<=',$data2);
        }
        // 通过模糊搜索到用户ID
        if (!empty($params['name'])) {
            $userId = User::where('name','like','%'.$params['name'].'%')->pluck('id');
            $query = $query->whereIn('user_id',$userId);
        }
        if (!empty($params['ip'])) {
            $query = $query->where('ip','like','%'.$params['ip'].'%');
        }
        if (!empty($params['permission'])){
            $query = $query->where('path',$params['permission']);
        }
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $list = $query->orderBy('id','desc')->paginate($limit);
        $list = $list->appends([
            'name'=>@$params['name'],
            'ip'=>@$params['ip'],
            'limit'=>$limit,
        ]);
        return $list;
    }
}
