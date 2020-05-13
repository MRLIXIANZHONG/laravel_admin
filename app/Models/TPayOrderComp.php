<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


class TPayOrderComp extends BaseModel
{
    protected $table = 'T_PayOrder_Comp';
    public $timestamps = false;
    protected $primaryKey = "id";//关键字

    //资金来源枚举及获取器
    const ISINCOME_YES = 1;
    const ISINCOME_NO = 0;
    public $IsincomeLabel = [
        self::ISINCOME_YES => '收入',
        self::ISINCOME_NO => '支出',
    ];
    public function getIsincomeTextAttribute(){
        return isset($this->IsincomeLabel[$this->Isincome]) ? $this->IsincomeLabel[$this->Isincome] : $this->Isincome;
    }

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

    const STATE_TREE = 1;
    const STATE_FALSE = 0;

    //账户COMP表[盟友账户交易日志]信息关联
    public function TPayOrderMain()
    {
        return $this->belongsTo('App\Models\TPayOrderMain','mOrderID','id');
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
        $query = $this::with('TPayOrderMain');
        if(!empty($params['PayType'])){
            $query = $query->where('PayType',$params['PayType']);
        }
        if (!empty($params['PayWay'])) {
            $query = $query->where('PayWay',$params['PayWay']);
        }
        if (!empty($params['year'])) {
            $query = $query->whereYear('PayTime',$params['year']);
        }
        if (!empty($params['month'])) {
            $query = $query->whereMonth('PayTime',$params['month']);
        }
//        if (!empty($params['类型?未确定字段'])) {
//            $query = $query->where('类型',$params['类型']);
//        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query
            ->where('Siteid',$params['siteid'])
            ->orderBy('PayTime', 'desc')->where('isDel',0)->where('state',self::STATE_TREE)->paginate($limit);
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
}