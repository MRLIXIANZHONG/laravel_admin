<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


class TPayOrderMain extends BaseModel
{
    protected $table = 'T_PayOrder_Main';
    public $timestamps = false;
    protected $primaryKey = "id";//关键字

    //支付方式枚举及获取器
    const PAYWAY_WECHAT = 1;
    const PAYWAY_ALIPAY = 2;
    const PAYWAY_BALANCE = 3;
    const PAYWAY_ADVANCE = 4;
    public $PayWayLabel = [
        self::PAYWAY_WECHAT => '微信支付',
        self::PAYWAY_ALIPAY => '阿里支付',
        self::PAYWAY_BALANCE => '余额支付',
        self::PAYWAY_ADVANCE => '预付费支付',
    ];
    public function getPayWayTextAttribute(){
        return isset($this->PayWayLabel[$this->PayWay]) ? $this->PayWayLabel[$this->PayWay] : $this->PayWay;
    }

    //账户COMP表[盟友账户交易日志]信息关联
    public function TPayOrderComp()
    {
        return $this->hasOne('App\Models\TPayOrderComp','mOrderID');
    }

    //账户USER表[用户账户交易日志]信息关联
    public function TPayOrderUser()
    {
        return $this->hasOne('App\Models\TPayOrderUser','mOrderID');
    }

    //获取盟友站点信息
    public function site3E21()
    {
        return $this->belongsTo('App\Models\Site3E21','Siteid','siteid');
    }

    //公司信息关联
    public function company()
    {
        return $this->belongsTo('App\Models\Company','compid','id');
    }

    //业务线信息关联
    public function tPayType()
    {
        return $this->belongsTo('App\Models\TPayType','PayType','id');
    }

    /**
     * model检索方法
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        $query = $this::with('TPayOrderComp');
        if(!empty($params['PayType'])){
            $query = $query->where('PayType',$params['PayType']);
        }
        if (!empty($params['PayWay'])) {
            $query = $query->where('PayWay',$params['PayWay']);
        }
        if (!empty($params['year'])) {
            $query = $query->whereYear('CreateTime',$params['year']);
        }
        if (!empty($params['month'])) {
            $query = $query->whereMonth('CreateTime',$params['month']);
        }
//        if (!empty($params['类型?未确定字段'])) {
//            $query = $query->where('类型',$params['类型']);
//        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query
            ->where('Siteid',$params['siteid'])
            ->orderBy('CreateTime', 'desc')->where('isdel',0)->paginate($limit);
        $appendData = $re->appends(array(
            'PayType' => @$params['PayType'],
            'PayWay' => @$params['PayWay'],
            'year' => @$params['year'],
            'month' => @$params['month'],
            'siteid' => @$params['siteid'],
            'limit' => @$params['limit'],
        ));
        return $re;
    }

    /**
     * 订单详情获取
     * Created by Lxd
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        $info = $this->setTable('main')->from('T_PayOrder_Main as main')
            ->leftJoin('T_PayOrder_Comp as comp','comp.mOrderID','=','main.id')
            ->leftJoin('T_PayOrder_User as user','user.mOrderID','=','main.id')
            ->select('main.*','comp.id as comppid','comp.Isincome as compIsincome','comp.uid as compuid','comp.CostRate as compCostRate','comp.CostMoney as compCostMoney','comp.RoyaltyRate as compRoyaltyRate','comp.RoyaltyMoney as compRoyaltyMoney','comp.SPrice as compSPrice','user.id as userid','user.Isincome as userIsincome','user.uid as useruid','user.CostRate as userCostRate','user.CostMoney as userCostMoney','user.RoyaltyRate as userRoyaltyRate','user.RoyaltyMoney as userRoyaltyMoney','user.SPrice as userSPrice')
            ->where('main.id',$id)
            ->first();
        return $info;
    }
}