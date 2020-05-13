<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


class Area3E21 extends BaseModel
{
    protected $table = 'area3e21';
    protected $primaryKey = "areaid";//关键字
    protected $guarded = [];

    public function getArea($params)
    {
        // 分页参数
        $page = isset($params['page']) ? $params['page'] : 1;
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $offset = ((int)$page - 1) * (int)$limit;

        $query = $this;
        //模糊搜索名称
        if(!empty($params['area'])){
            $query = $query->where('area','like','%'.$params['area'].'%');
        }
        //获取下级
        if(!empty($params['next_area'])){
            $query = $query->where('lishu',$params['next_area']);
        }
        if(!empty($params['level'])){
            $query = $query->where('level',$params['level']);
        }
        $list = $query->orderBy('view_lev', 'asc')
            ->select('areaid','area','flag')
            ->offset($offset)->limit($limit)->get()->toArray();

        if (count($list)) {
            return ['code'=>200,'infor'=>$list];
        } else {
            return $page == 1 ? ['code'=>404,'infor'=>false] : ['code'=>404,'infor'=>'已无数据'];
        }
    }
}