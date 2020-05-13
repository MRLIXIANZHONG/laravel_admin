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

//提现申请

class CashApply extends BaseModel {
   protected $table ='user_cash_apply';


   //查询余额表
   public function search($params){
       $query = PayAgent::query();
       //查询
       $limit = isset($params['limit']) ? $params['limit'] : 10;
       if (getAuth()->siteid ==1){
           if(!empty($params['siteid'])){
               $query = $query->where('t_pay_agent.siteid','=',$params['siteid']);
           }
           $re = $query->leftJoin('site_3e21','t_pay_agent.siteid','site_3e21.siteid')->select('t_pay_agent.*','site_3e21.site_name')->orderBy('id', 'desc')->paginate($limit);

       }else{

           $re=  $query->leftJoin('site_3e21','t_pay_agent.siteid','site_3e21.siteid')->select('t_pay_agent.*','site_3e21.site_name')->where('t_pay_agent.siteid',getAuth()->siteid)->paginate($limit);

       }

       $appendData = $re->appends(array(
           'siteid' => @$params['siteid'],
           'limit' => @$params['limit'],
       ));

       return $re;

   }

   //提现申请
    public function carry($params){
       $siteid=getAuth()->siteid;

       //查询账户余额
        $yue=DB::table('t_pay_agent')->where('siteid',$siteid)->value('fund');

        //digits_between:min,max
        $validator = Validator::make($params,[
            'money' => 'required|numeric|between:0,'.$yue,

        ], [
            'money.required'=>'请填写提现金额',
            'money.between'=>'余额不足,或输入金额为负',
            'money.numeric'=>'金额必须为数字类型',
        ]);
       $va = $validator->passes() ? true : $validator->errors()->first();
        if ($va !== true){
            return response()->json(['code' => 201, 'infor' => $va,]);
        }

        //开启事务
        DB::beginTransaction();
        try{

            //拼凑数据
            $data=$this->set_data($params);
            $cash_apply=$data['cash_apply'];//提现记录
            $main=$data['main'];//主订单日志
            $comp=$data['comp'];//副订单日志
            //原账户余额
            $cash_apply['prevmoney']=$yue;


            $comp['prevmoney']=$yue;//提现前的余额
            $comp['nowmoney']=$yue - $params['money'];//提现后的余额

            //添加提现记录
            $cash_apply_id= DB::table('user_cash_apply')->insertGetId($cash_apply);

            //添加主订单信息
            $main['InfoID']=$cash_apply_id;//关联订单id
            $main_id= DB::table('T_PayOrder_Main')->insertGetId($main);
            $comp['mOrderID']=$main_id;
            $comp['InfoID']=$cash_apply_id;//关联订单id
            //添加副订单
             DB::table('T_PayOrder_Comp')->insertGetId($comp);

            //减去余额
            DB::table('t_pay_agent')->where('siteid',$siteid)->decrement('fund',$params['money']);

            DB::commit();
            return response()->json(['code' =>200, 'infor' => '提现申请已提交，等待审核']);

        }catch (\Exception $e){

            DB::rollBack();

            return response()->json(['code' => 302, 'infor' => '网络异常,提现失败',]);
        }



    }

    //提现审核列表
    public function examine_list($params){

        $query = self::query();
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        if (getAuth()->siteid ==1){
            if(!empty($params['siteid'])){
                $query = $query->where('user_cash_apply.siteid','=',$params['siteid']);
            }

            $re = $query->leftJoin('site_3e21','user_cash_apply.siteid','site_3e21.siteid')->select('user_cash_apply.*','site_3e21.site_name')->orderBy('user_cash_apply.state', 'asc')->paginate($limit);

        }else{
            $re=  $query->leftJoin('site_3e21','user_cash_apply.siteid','site_3e21.siteid')->select('user_cash_apply.*','site_3e21.site_name')->where('user_cash_apply.siteid',getAuth()->siteid)->paginate($limit);
        }
        $appendData = $re->appends(array(
            'siteid' => @$params['siteid'],
            'limit' => @$params['limit'],
        ));

        return $re;
    }


    //数据拼凑添加提现记录
    public function set_data($params){
        //提现记录数据
        $data['uid']=getAuth()->id;
        $data['money']=$params['money'];
        $data['addtime']=$_SERVER['REQUEST_TIME'];
        $data['remarks']='余额提现';
        $data['siteid']=getAuth()->siteid;
        $data['openid']=getAuth()->openid;

        //查询站点信息
        $site=DB::table('site_3e21')->where('siteid',getAuth()->siteid)->first();

        //主订单数据
        $data1['Siteid']=getAuth()->siteid;
        $data1['compid']=$site->companyno;
        $data1['PayType']=404;//支付类型
        // $data1['InfoID']='';//关联信息id
        $data1['utype']=2;//盟友账户
        $data1['Uid']=getAuth()->id;
        $data1['PayName']=getAuth()->username;//支付人账号
        $data1['Price']=$params['money'];
        $data1['Price2']=$params['money'];
        $data1['AttachInfo']='余额提现';
        $data1['OverInfo']=date('YmdHis') . rand(10000000,99999999);//生成唯一的订单号
        $data1['PayTime']=getDateTime();//支付时间
        $data1['PayWay']=1;//微信支付
        $data1['result']='success';
        //副订单
        // $data2['mOrderID']='';//主订单id
        $data2['Siteid']=$data1['Siteid'];
        //$data2['InfoID']=$data1['InfoID'];
        $data2['uid']=$data1['Uid'];
        $data2['formtype']=2;//盟友账户
        $data2['formUid']=getAuth()->id;
        $data2['Price']=$params['money'];
        $data2['Price2']=$params['money'];
        $data2['Isincome']=0;//支出
        $data2['AttachInfo']='余额提现';
        $data2['PayType']=404;//支付类型
        $data2['OverInfo']=$data1['OverInfo'];
        $data2['PayTime']=$data1['PayTime'];
        $data2['PayWay']=1;//微信支付
        //  $data2['prevmoney']='';//原账户余额
        //$data2['nowmoney']='';//提现后的余额
        $data2['CreateTime']=$data1['PayTime'];
        return array('cash_apply'=>$data,'main'=>$data1,'comp'=>$data2);

    }

    //拒绝提现主副订单日志
    public function zhu_fu($cash_app_id){

        //查询提现记录
        $cash_app=DB::table('user_cash_apply')->where('id',$cash_app_id)->first();

        if ($cash_app){

            //查询站点信息
            $site=DB::table('site_3e21')->where('siteid',$cash_app->siteid)->first();

            //主订单数据
            $data1['Siteid']=$cash_app->siteid;
            $data1['compid']=$site->companyno;
            $data1['PayType']=404;//支付类型
            // $data1['InfoID']='';//关联信息id
            $data1['utype']=2;//盟友账户
            $data1['Uid']=$cash_app->uid;
            $data1['PayName']=DB::table('users')->where('id',$cash_app->uid)->value('username');//支付人账号
            $data1['Price']=$cash_app->money;
            $data1['Price2']=$cash_app->money;
            $data1['AttachInfo']='余额提现';
            $data1['OverInfo']=date('YmdHis') . rand(10000000,99999999);//生成唯一的订单号
            $data1['PayTime']=getDateTime();//支付时间
            $data1['PayWay']=1;//微信支付
            $data1['result']='success';
            //副订单
            // $data2['mOrderID']='';//主订单id
            $data2['Siteid']=$data1['Siteid'];
           // $data2['InfoID']=$data1['InfoID'];
            $data2['uid']=$data1['Uid'];
            $data2['formtype']=2;//盟友账户
            $data2['formUid']=$cash_app->uid;
            $data2['Price']=$cash_app->money;
            $data2['Price2']=$cash_app->money;
            $data2['Isincome']=1;//收入
            $data2['AttachInfo']='余额提现';
            $data2['PayType']=404;//支付类型
            $data2['OverInfo']=$data1['OverInfo'];
            $data2['PayTime']=$data1['PayTime'];
            $data2['PayWay']=1;//微信支付
            //  $data2['prevmoney']='';//原账户余额
            //$data2['nowmoney']='';//提现后的余额
            $data2['CreateTime']=$data1['PayTime'];
            $data2['prevmoney']=$cash_app->prevmoney;//原账户余额
            $data2['nowmoney']=$cash_app->prevmoney - $cash_app->money;//扣款后的余额
            return array('main'=>$data1,'comp'=>$data2);
        }

        return false;
    }


    //提现审核通过
    public function examine_yes($params){

        //开启事务
        DB::beginTransaction();
        try{

            //更新审核状态
            DB::table('user_cash_apply')->where('id',$params['cash_id'])->update(array('state'=>1));//通过审核
            //TODO ;; 调用支付接口
            //查询提现记录
            $cash_app=DB::table('user_cash_apply')->where('id',$params['cash_id'])->first();
           // $result=cash_out($cash_app->openid,$params['cash_id'],$cash_app->money*100,'提现',404,$cash_app->siteid,$cash_app->uid);
            $result=cash_out($cash_app->openid,$params['cash_id'],40,'提现',404,$cash_app->siteid,$cash_app->uid);
            file_put_contents('./tixian.txt',$result);
            DB::commit();
            return response()->json(['code' =>200, 'infor' => '提现已经审核']);

        }catch (\Exception $e){

            DB::rollBack();

            return response()->json(['code' => 302, 'infor' => '网络异常,审核失败',]);
        }


    }

    //拒绝审核
    public function examine_no($params){
        //开启事务
        DB::beginTransaction();
        try{
            $user_cash= DB::table('user_cash_apply')->where('id',$params['cash_id'])->first();


            $data=$this->zhu_fu($params['cash_id']);
            if ($data == false){
                //抛出异常
                throw new \Exception('网络异常,审核失败');
            }

            $main=$data['main'];//主订单数据
            $comp=$data['comp'];//副订单数据

            $main['InfoID']=$params['cash_id'];//资源id
            //添加主订单信息
            $main['InfoID']=$params['cash_id'];//关联订单id
            $main_id= DB::table('T_PayOrder_Main')->insertGetId($main);
            $comp['mOrderID']=$main_id;
            //添加副订单
            $comp['InfoID']=$params['cash_id'];//关联订单id
            DB::table('T_PayOrder_Comp')->insertGetId($comp)
            ;
            //更新审核状态
            DB::table('user_cash_apply')->where('id',$params['cash_id'])->update(array('state'=>2,'reply'=>$params['reply']));//拒绝提现
            //余额添加
            DB::table('t_pay_agent')->where('siteid',$user_cash->siteid)->increment('fund',$user_cash->money);

            DB::commit();
            return response()->json(['code' =>200, 'infor' => '已经拒绝提现']);

        }catch (\Exception $e){

            DB::rollBack();

            return response()->json(['code' => 302, 'infor' => '网络异常,拒绝失败',]);
        }




    }


    /**
     * @param $parms
     * @param string $type
     * @return bool
     * 验证数据
     */
    public function Validator_SmsModel($parms,$type = 'insert'){
      /*  foreach ($parms as $v){
            if(!is_string($v)) continue;
            if((new BaseModel)->Validator_SensitiveHelper($v,'detect'))
                return '表单含有敏感词内容,请重新输入';
        }*/
        $rules = [];
        $message = [];
        foreach ($parms as $k=>$v){
            switch ($k) {
                case 'user_id' :
                    $rules['user_id'] = 'required|integer';
                    $message['site.required'] = '请选择商家';
                    $message['site.integer'] = 'siteid数据类型错误';
                    break;
                case 'msg_count':
                    $rules['msg_count'] = 'required';
                    $message['password.required'] = '请填写初始短信条数';
            }
        }
        $validator = Validator::make($parms, $rules, $message);
        return $validator->passes() ? true : $validator->errors()->first();
    }


}
