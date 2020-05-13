<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


use Illuminate\Support\Facades\DB;

class Site3E21 extends BaseModel
{
    protected $table = 'site_3e21';
    public $timestamps = false;
    protected $primaryKey = "siteid";//关键字

    //是否加盟枚举及获取器
    const ISJF_YES = 1;
    const ISJF_NO = 0;
    public $isjfLabel = [self::ISJF_YES => '是', self::ISJF_NO => '否',];
    public function getIsjfTextAttribute(){
        return isset($this->isjfLabel[$this->isjf]) ? $this->isjfLabel[$this->isjf] : $this->isjf;
    }

    //公司信息关联
    public function company()
    {
        return $this->belongsTo('App\Models\Company','companyno','id');
    }

    //账户信息关联
    public function TPayOrderComp()
    {
        return $this->hasMany('App\Models\TPayOrderComp','Siteid','siteid');
    }

    //账户余额信息关联
    public function TPayAgent()
    {
        return $this->hasOne('App\Models\TPayAgent','siteid','siteid');
    }

    /**
     * 区域信息获取
     * Created by Lxd
     * @param $info
     * @return mixed
     */
    public function getArea3e21Info($info)
    {
        return Area3E21::whereIn('areaid',[$info->areano,$info->dsid,$info->sfid])
            ->orderBy('level','asc')->get();
    }

//    public function getNotTpaySite()
//    {
//        $_payIsteId = Tpay::where('isdel',0)->pluck('siteid');
//        $re = $this->whereNotIn('siteid',$_payIsteId)->where('isdel',0)->get();
//        dd($re);
//    }

    /**
     * model检索方法
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        $query = $this;
        if(!empty($params['site_name'])){
            $query = $query->where('site_name','like','%'.$params['site_name'].'%');
        }
        if (!empty($params['city_type'])) {
            $query = $query->where('city_type','like', '%'.$params['city_type'].'%');
        }
        if (!empty($params['areatitle'])) {
            $query = $query->where('areatitle','like', '%'.$params['areatitle'].'%');
        }
        if (!empty($params['siteid'])) {
            $query = $query->where('siteid',$params['siteid']);
        }
        if (getAuth()->siteid !== User::SUPERADMIN){
            $query = $query->where('siteid',getAuth()->siteid);
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->orderBy('siteid', 'desc')->where('isdel',0)->paginate($limit);
        $appendData = $re->appends(array(
            'site_name' => @$params['site_name'],
            'city_type' => @$params['city_type'],
            'areatitle' => @$params['areatitle'],
            'limit' => @$params['limit'],
        ));
        return $re;
    }

    /**
     * model检索方法|带余额等统计
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function searchWithAgent($params)
    {
        $query = $this->setTable('site')->from('site_3e21 as site')
            ->leftJoin('t_pay_agent as agent','agent.siteid','=','site.siteid');
        if(!empty($params['site_name'])){
            $query = $query->where('site.site_name','like','%'.$params['site_name'].'%');
        }
        if(!empty($params['companyno'])){
            $query = $query->where('site.companyno',$params['companyno']);
        }
        if (!empty($params['siteid'])) {
            $query = $query->where('site.siteid',$params['siteid']);
        }
        if (isset($params['isjf']) && $params['isjf'] !== "") {
            $query = $query->where('site.isjf',$params['isjf']);
        }
        if (getAuth()->siteid !== User::SUPERADMIN){
            $query = $query->where('site.siteid',getAuth()->siteid);
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query
            ->select('site.*','agent.fund','agent.ownfund','agent.money_take','agent.userfund','agent.userfcfund','agent.lockbalance','agent.ischk','agent.totalprice')
            ->orderBy('agent.fund', 'desc')->where('site.isdel',0)->paginate($limit);
//        $re = $query->withCount(['TPayOrderComp as comp_sum' => function($query1){
//            $query1->select(DB::raw("sum(Price2) as comp_sum"));
//        }])->orderBy('comp_sum', 'desc')->where('isdel',0)->paginate($limit);
        $appendData = $re->appends(array(
            'site_name' => @$params['site_name'],
            'companyno' => @$params['companyno'],
            'siteid' => @$params['siteid'],
            'isjf' => @$params['isjf'],
            'limit' => @$params['limit'],
        ));
        return $re;
    }

    /**
     * ajax|站点分页获取
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function getSite($params)
    {
        // 分页参数
        $page = isset($params['page']) ? $params['page'] : 1;
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $offset = ((int)$page - 1) * (int)$limit;

        $query = $this;
        if(!empty($params['site_name'])){
            $query = $query->where('site_name','like','%'.$params['site_name'].'%');
        }
        $list = $query->orderBy('siteid', 'desc')->where('isdel',0)
            ->select('siteid','site_name','weburl')
            ->offset($offset)->limit($limit)->get()->toArray();

        if (count($list)) {
            return ['code'=>200,'infor'=>$list];
        } else {
            return $page == 1 ? ['code'=>404,'infor'=>false] : ['code'=>404,'infor'=>'已无数据'];
        }
    }

    /**
     * 添加
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function add($params)
    {
        $params['addtime'] = getDateTime();
        //$params['is_new']=1;//新添加的站点
        $insert = $this->insert($params);
        if($insert){
            return ['code' => 200, 'infor' => '添加成功'];
        }else{
            return ['code' => 456, 'infor' => '网络异常,添加失败'];
        }
    }

    /**
     * 修改
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function edit($params)
    {
        try {
            $update = $this->where('siteid', $params['siteid'])->update($params);
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