<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


class Tpay extends BaseModel
{
    protected $table = 't_pay';
    public $timestamps=false;
    protected $primaryKey="id";//关键字

    //支付宝PC扫码支付枚举及获取器
    const ISALI_NO = 0;
    const ISALI_YES = 1;
    public $isaliLabel = [
        self::ISALI_NO => '关闭',
        self::ISALI_YES => '已开启',
    ];
    public function getIsaliTextAttribute()
    {
        return $this->isaliLabel[$this->isali] ?? $this->isali;
    }

    //支付宝手机网页端支付枚举及获取器
    const ISALIPHONE_NO = 0;
    const ISALIPHONE_YES = 1;
    public $isaliphoneLabel = [
        self::ISALIPHONE_NO => '关闭',
        self::ISALIPHONE_YES => '已开启',
    ];
    public function getIsaliphoneTextAttribute()
    {
        return $this->isaliphoneLabel[$this->isaliphone] ?? $this->isaliphone;
    }

    //支付宝APP支付枚举及获取器
    const ISALIPAYAPP_NO = 0;
    const ISALIPAYAPP_YES = 1;
    public $IsAlipayAppLabel = [
        self::ISALIPAYAPP_NO => '关闭',
        self::ISALIPAYAPP_YES => '已开启',
    ];
    public function getIsAlipayAppTextAttribute()
    {
        return $this->IsAlipayAppLabel[$this->IsAlipayApp] ?? $this->IsAlipayApp;
    }

    //是否开通微信支付枚举及获取器
    const ISWX_NO = 0;
    const ISWX_YES = 1;
    public $isWxLabel = [
        self::ISWX_NO => '关闭',
        self::ISWX_YES => '已开启',
    ];
    public function getisWxTextAttribute()
    {
        return $this->isWxLabel[$this->isWx] ?? $this->isWx;
    }

    //获取盟友信息
    public function site3E21()
    {
        return $this->belongsTo('App\Models\Site3E21','siteid','siteid');
    }

    /**
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params){
        $query = $this;
        if(!empty($params['compname'])){
            $query = $query->where('compname','like','%'.$params['compname'].'%');
        }
        if (getAuth()->siteid !== User::SUPERADMIN){
            $query = $query->where('siteid',getAuth()->siteid);
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->orderBy('id', 'desc')->where('isdel',0)->paginate($limit);
        $appendData = $re->appends(array(
            'compname' => @$params['compname'],
            'limit' => @$params['limit'],
        ));
        return $re;
    }

    /**
     * 添加
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function add($params)
    {
        try {
            $params['id'] = $this->max('id') + 1;
            $insert = $this->insert($params);
            if ($insert) {
                return ['code' => 200, 'infor' => '添加站点支付配置成功'];
            }
            return ['code' => 456, 'infor' => '网络异常,添加失败'];
        }catch (\Exception $e){
            return ['code' => 500, 'infor' => $e->getMessage()];
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
            $update = $this->where('id', $params['id'])->update($params);
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