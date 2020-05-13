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

class Sms extends BaseModel {
   protected $table ='sms_count';

    //获取盟友信息
    public function site3E21()
    {
        return $this->belongsTo('App\Models\Site3E21','siteid','siteid');
    }

   //查询数据
    public function get_list($params){

        //查询
        $query = $this;
        if (getAuth()->siteid !== User::SUPERADMIN){
            $query = $query->where('siteid',getAuth()->siteid);
            //查询
            $limit = isset($params['limit']) ? $params['limit'] : 10;
            $re = $query->orderBy('id', 'desc')->paginate($limit);
            $appendData = $re->appends(array(
                'user_name' => @$params['user_name'],
                'limit' => @$params['limit'],
            ));
            if (count($re) ==0){
                //添加短信
                DB::table('sms_count')->insertGetId(array(
                   'siteid'=>getAuth()->siteid,
                    'msg_count'=>0,
                    'status'=>0,
                ));
            }

        }else{
            //查询
            $limit = isset($params['limit']) ? $params['limit'] : 10;
            $re = $query->orderBy('id', 'desc')->paginate($limit);
            $appendData = $re->appends(array(
                'user_name' => @$params['user_name'],
                'limit' => @$params['limit'],
            ));
        }
        return $re;
    }

    //添加数据
    public function create_sms($params){
        $validatorhelp = $this->Validator_SmsModel($params,'insert');
        if($validatorhelp !== true){
            return response()->json(['code' => 201, 'infor' => $validatorhelp,]);
        }
        if($insertId = $this->insertGetId($params)){
            return response()->json(['code' =>200, 'infor' => '商家短信添加成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);

    }

    //短信充值
    public function recharge($params){
        if (!$params['msg_count']){
            return response()->json(['code' =>201, 'infor' => '请填写充值条数']);
        }
        if ($params['msg_count']< 0){
            return response()->json(['code' =>201, 'infor' => '请填写大于零的值']);
        }
       /* if(!preg_match("/^[1-9][0-9]*$/" ,$params['msg_count'])){
            return response()->json(['code' =>201, 'infor' => '请填写正整数']);
        }*/

        if (!$params['id']){
            return response()->json(['code' =>201, 'infor' => '网络异常，充值失败']);
        }

        //拼凑数据
        $sms_count=$this->where('id',$params['id'])->first();

        $data['user_id']=getAuth()->id;
        $data['siteid']=$sms_count['siteid'];
        $data['msg_count']=$params['msg_count'];//充值条数
        $data['money']=$params['msg_count'] * 0.08;//订单金额
        if ($params['remarks']){
            $data['remarks']=$params['remarks'];//订单备注
        }
        $data['order_number']=time(). rand(10000000,99999999);//生成唯一的订单号

        $data['created_at']=getDateTime();


     //开启事务
        DB::beginTransaction();
        try{
            $id=DB::table('sms_recharge')->insertGetId($data);
            if (!$id){
                //抛出异常
                throw new \Exception('充值失败');
            }

            //调用支付
            $url=GetwechatPay($id,getAuth()->id,3,1,1,0,0,0.01,0.01,0,1130, $data['siteid'],0,0,getAuth()->username);

            DB::commit();

            return response()->json(['code' =>200, 'infor' => '充值申请已提交','url'=>$url]);
        }catch (\Exception $e){

            DB::rollBack();

            return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);
        }




    }


    //删除商家短信
    public function delete_sms($id)
    {
        //查询商家
        $sms_count=DB::table('sms_count')->where('id',$id)->first();
        if ($sms_count ){
            if ($sms_count->msg_count >0){
                return response()->json(['code' =>201, 'infor' => '商家短信未使用完，不能删除']);
            }
        }
        //删除
        DB::table('sms_count')->where('id',$id)->delete();
        return response()->json(['code' =>200, 'infor' => '删除成功']);

    }

    //处理支付成功的逻辑
    public function set_sms($order_id){
        $order =DB::table('sms_recharge')->where('id',$order_id)->first();
        //更新短信条数
          DB::table('sms_count')->where('siteid',$order->siteid)->increment('msg_count',$order->msg_count);
        //更改状态
         DB::table('sms_count')->where('siteid',$order->siteid)->update(array('status'=>1));
        //更改订单状态
        DB::table('sms_recharge')->where('id',$order_id)->update(array('status'=>1,'updated_at'=>getDateTime()));
    }


    //查询短信发送日志
    public function get_log($params){
        $query = SmsLog::query();
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        if (getAuth()->siteid ==1){
            if(!empty($params['siteid'])){
                $query = $query->where('sms_log.siteid','=',$params['siteid']);
            }
            $re = $query->leftJoin('site_3e21','sms_log.siteid','site_3e21.siteid')->select('sms_log.*','site_3e21.site_name')->orderBy('sms_log.id', 'desc')->paginate($limit);

        }else{

            $re=  $query->leftJoin('site_3e21','sms_log.siteid','site_3e21.siteid')->select('sms_log.*','site_3e21.site_name')->where('sms_log.siteid',getAuth()->siteid)->paginate($limit);

        }

        $appendData = $re->appends(array(
            'siteid' => @$params['siteid'],
            'limit' => @$params['limit'],
        ));

        return $re;

    }

    //查询短信充值日志
    public function recharge_log($params){
        $query = SmsRecharge::query();
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        if (getAuth()->siteid ==1){
            if(!empty($params['siteid'])){
                $query = $query->where('sms_recharge.siteid','=',$params['siteid']);
            }
            $re = $query->leftJoin('site_3e21','sms_recharge.siteid','site_3e21.siteid')->select('sms_recharge.*','site_3e21.site_name')->orderBy('sms_recharge.id', 'desc')->paginate($limit);

        }else{

            $re=  $query->leftJoin('site_3e21','sms_recharge.siteid','site_3e21.siteid')->select('sms_recharge.*','site_3e21.site_name')->where('sms_recharge.siteid',getAuth()->siteid)->paginate($limit);

        }

        $appendData = $re->appends(array(
            'siteid' => @$params['siteid'],
            'limit' => @$params['limit'],
        ));

        return $re;


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
                case 'siteid' :
                    $rules['siteid'] = 'required|integer';
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
