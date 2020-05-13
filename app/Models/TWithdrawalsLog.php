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

//用户提现

class TWithdrawalsLog extends BaseModel {
   protected $table ='T_Withdrawals_Log';


   //查询用户提现记录
   public function search($params){
       $query = self::query();
       //查询
       $limit = isset($params['limit']) ? $params['limit'] : 10;
       $re=[];
       if (getAuth()->siteid ==1){

           $query->where('T_Withdrawals_Log.TradeType','=',1);//查询提现的
           //站点名称
           if(!empty($params['site_name'])){

               $query = $query->where('site_3e21.site_name','like','%'.$params['site_name'].'%');
           }

           if (!empty($params['date1']) && !empty($params['date2'])){
               $data1 = date('Y-m-d H:i:s',$params['date1']);
               $data2 = date('Y-m-d H:i:s',$params['date2']);
               $query = $query->where('T_Withdrawals_Log.ApplyTime','>=',$data1)->where('T_Withdrawals_Log.ApplyTime','<=',$data2);
           }

           $re = $query
               ->leftJoin('site_3e21','T_Withdrawals_Log.SiteID','site_3e21.siteid')
               ->leftJoin('T_Users','T_Withdrawals_Log.UserID','T_Users.id')
               ->leftJoin('T_User_Wallet','T_Withdrawals_Log.UserID','T_User_Wallet.UserID')
               ->select('T_Withdrawals_Log.*','site_3e21.site_name','T_Users.username','T_User_Wallet.balance')
               ->orderBy('T_Withdrawals_Log.State', 'asc')
               ->paginate($limit);

       }
       $appendData = $re->appends(array(
           'site_name' => @$params['site_name'],
           'limit' => @$params['limit'],
       ));

       return $re;

   }
   //用户提现弹窗
    public function popup($params){
        if (!$params['id']){
            return [];
        }
        //查询提现记录
        $twl=DB::table('T_Withdrawals_Log')
            ->leftJoin('site_3e21','T_Withdrawals_Log.SiteID','=','site_3e21.siteid')
            ->leftJoin('T_User_Wallet','T_Withdrawals_Log.UserID','=','T_User_Wallet.UserID')
            ->where('T_Withdrawals_Log.Id',$params['id'])
            ->select('T_Withdrawals_Log.*','site_3e21.site_name','T_User_Wallet.balance')
            ->first();



       return $twl;
    }

    //用户提现操作
    public function operation($params){
        if (!$params['id']){
            return response()->json(['code' => 302, 'infor' => '网络异常,操作失败',]);
        }
        if (!$params['utype']){
            return response()->json(['code' => 302, 'infor' => '网络异常,操作失败',]);
        }

        //查询提现记录
        $twl=DB::table('T_Withdrawals_Log')
            ->where('Id',$params['id'])
            ->first();
        if ($twl->State !=0){//已经操作过了
            return response()->json(['code' => 302, 'infor' => '网络异常,操作失败',]);
        }
        //操作提现
        switch ($params['utype']){
            case 1://拒绝提现
               $exe= $this->examine_no($params);
                break;
            case 2://同意提现
                $exe=$this->examine_yes($params);
                break;
        }
        return $exe;
    }


    //用户提现流水
    public function water($params){
        if (!$params['id']){
            return response()->json(['code' => 302, 'infor' => '网络异常,操作失败',]);
        }
        //查询站点id
        $user_id=DB::table('T_Withdrawals_Log')->where('Id',$params['id'])->value('UserID');

        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = DB::table('T_PayOrder_User')
            ->leftJoin('site_3e21','T_PayOrder_User.Siteid','site_3e21.siteid')
            ->leftJoin('T_Users','T_PayOrder_User.uid','T_Users.id')
            ->leftJoin('T_Withdrawals_Log','T_PayOrder_User.InfoID','T_Withdrawals_Log.Id')
            ->select('T_PayOrder_User.*','site_3e21.site_name','T_Users.username','T_Withdrawals_Log.State as tstatus')
            ->where('T_PayOrder_User.uid',$user_id)
            ->where('T_PayOrder_User.state',1)
            ->where('T_PayOrder_User.IsDel',0)
            ->orderBy('T_PayOrder_User.id', 'desc')
            ->paginate($limit);
        $appendData = $re->appends(array(
            'limit' => @$params['limit'],
        ));

        return $re;
    }




    //拒绝提现添加日志
    public function jujue($tw){

        //查询会员的余额
        $balance=DB::table('T_User_Wallet')->where("UserID",$tw->UserID)->value('balance');
        if ($tw){

            //添加消费主记录
            $data1['SiteID']=$tw->SiteID;
            $data1['TradeType']=$tw->TradeType;
            $data1['Type']=$tw->Type;
            $data1['UserID']=$tw->UserID;
            $data1['UserName']=$tw->UserName;
            $data1['ApplyMoney']=$tw->ApplyMoney;
            $data1['PayMoney']=$tw->PayMoney;
            $data1['OrderNum']=date('YmdHis') . rand(10000000,99999999);//生成唯一的订单号
            $data1['Source']=$tw->Source;
            $data1['Tel']=$tw->Tel;
            $data1['State']=2;//已拒绝
            $data1['ApplyTime']=getDateTime();//时间
            $data1['Memo']='拒绝提现';
            $data1['CreateTime']=getDateTime();//时间

            //拒绝提现流水
            $data2['Siteid']=$tw->SiteID;
           // $data2['InfoID']=$tw->Id;
            $data2['uid']=$tw->UserID;
            $data2['formtype']=3;//用户账号
            $data2['Price']=$tw->ApplyMoney;
            $data2['Price2']=$tw->ApplyMoney;
            $data2['state']=1;
            $data2['Isincome']=1;
            $data2['AttachInfo']='拒绝提现';
            $data2['PayType']=404;
            $data2['OverInfo']=date('YmdHis') . rand(10000000,99999999);//生成唯一的订单号
            $data2['PayTime']=getDateTime();//时间
            $data2['prevmoney']=$balance;//操作前的余额
            $data2['nowmoney']=$balance + $tw->ApplyMoney;//操作后的余额
            $data2['SPrice']=$tw->ApplyMoney;//实际金额
            $data2['CreateTime']=getDateTime();//时间

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
            DB::table('T_Withdrawals_Log')->where('Id',$params['id'])->update(array('State'=>1));//通过审核
            //TODO ;; 调用支付接口
            //查询提现记录
            $cash_app=DB::table('T_Withdrawals_Log')
                ->leftJoin('T_UserWithdrawals_Set','T_Withdrawals_Log.UserID','T_UserWithdrawals_Set.UserID')//查询user的openid
                ->select('T_Withdrawals_Log.*','T_UserWithdrawals_Set.OpenId')
                ->where('T_Withdrawals_Log.Id',$params['id'])
                ->first();
           // $result=cash_out($cash_app->openid,$params['cash_id'],$cash_app->money*100,'提现',404,$cash_app->siteid,$cash_app->uid);
            $result=cash_out($cash_app->OpenId,$params['id'],$cash_app->ApplyMoney * 100,'提现',404,$cash_app->SiteID,$cash_app->UserID);
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
            //查询提现记录
            $tw=DB::table('T_Withdrawals_Log')->where('Id',$params['id'])->first();

            $data=$this->jujue($tw);
            if ($data == false){
                //抛出异常
                throw new \Exception('网络异常,审核失败');
            }
            //添加主订单数据
            $min_id= DB::table('T_Withdrawals_Log')->insertGetId($data['main']);
            //添加数据
            $data['comp']['InfoID']=$min_id;
            $tp_id= DB::table('T_PayOrder_User')->insertGetId($data['comp']);

            //更新审核状态
            DB::table('T_Withdrawals_Log')->where('Id',$params['id'])->update(array('State'=>2));//拒绝提现
            //余额添加
            DB::table('T_User_Wallet')->where('UserID',$tw->UserID)->increment('balance',$tw->ApplyMoney);

            DB::commit();
            return response()->json(['code' =>200, 'infor' => '已经拒绝提现']);

        }catch (\Exception $e){

            DB::rollBack();

            return response()->json(['code' => 302, 'infor' => '网络异常,拒绝失败',]);
        }


    }





}
