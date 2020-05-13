<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sms;
use App\Models\WechatConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsController extends BaseController{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 短信商家列表
     */
    public function index(Request $request)
    {
        //dd(getAuth()->siteid);
        $params = $request->input();
        $re=(new Sms())->get_list($params);
        return view("Admin.sms.index",compact("re"));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 初始添加短信
     */
    public function create(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit','role');
            $execute=(new Sms())->create_sms($params);
            return $execute;
        }
        //查询站点
        $site=DB::table('site_3e21')->where('siteid','<>',1)->select('siteid','site_name')->get();
        return view('Admin.sms.create',compact('site'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 短信充值
     */
    public function recharge(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');

            $execute=(new Sms())->recharge($params);
            return $execute;
        }
        $data['id'] = $request->get('id');

        return view('Admin.sms.recharge',compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 删除商家短信
     */
    public function delete(Request $request){

        $id=$request->input('id');
        $execute=(new Sms())->delete_sms($id);
        return $execute;
    }

    /**
     * @param Request $request
     * @return string
     * 短信充值回调
     */
    public function callback(Request $request){

        $data=$request->all();
        //file_put_contents('./data2.txt',$data);
        $data=array_keys($data)[0];
        $res=json_decode($data,true);
        //file_put_contents('./data2.txt',$res['result']);

        if ($res['result']='ok'){
            //支付成功
            (new Sms())->set_sms($res['id']);
        }
        return 'success';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 短信发送日志
     */
    public function smsLog(Request $request){
        $params = $request->input();
        $re = (new Sms())->get_log($params);
        //查询所以站点
        $site=DB::table('site_3e21')->where('siteid','<>',1)->select('siteid','site_name')->get();
        return view("Admin.sms.smslog",compact("re","site"));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 短信充值日志
     */
    public function smsRechargeLog(Request $request){
        $params = $request->input();
        $re = (new Sms())->recharge_log($params);
        //查询所以站点
        $site=DB::table('site_3e21')->where('siteid','<>',1)->select('siteid','site_name')->get();
        return view("Admin.sms.rechargelog",compact("re","site"));


    }



}