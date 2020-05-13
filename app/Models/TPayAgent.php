<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


class TPayAgent extends BaseModel
{
    protected $table = 't_pay_agent';
    public $timestamps = false;
    protected $primaryKey = "id";//关键字


    /**
     * 统计金额计算
     * Created by Lxd
     * @param null $siteid
     * @return mixed
     */
    public function getCountSum($siteid = null)
    {
        $query = $this;
        if($siteid){
            $query = $query->where('siteid',$siteid);
        }
        $data = $query->get(
            array(
                //总余额[当前/总站点钱包剩余的钱]
                \DB::raw('SUM(fund) as fund'),
                //总收入[当前/总站点收入的钱]
                \DB::raw('SUM(totalprice) as totalprice'),
            )
        );
        return $data[0];
    }
}