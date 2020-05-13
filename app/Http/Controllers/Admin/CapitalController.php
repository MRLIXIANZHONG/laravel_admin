<?php

namespace App\Http\Controllers\Admin;

use App\Models\CashApply;
use App\Models\Sms;
use App\Models\WechatConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class CapitalController
 * @package App\Http\Controllers\Admin
 * 账户金额管理
 */
class CapitalController extends BaseController{


    //查询余额列表
    public function index(Request $request){
        $params = $request->input();
        $re = (new CashApply())->search($params);
        //查询所以站点
        $site=DB::table('site_3e21')->where('siteid','<>',1)->select('siteid','site_name')->get();
        return view("Admin.capital.index",compact("re","site"));
    }

    //提现
    public function carry(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');

            $execute=(new CashApply())->carry($params);
            return $execute;
        }
        return view('Admin.capital.carry');
    }

    //提现记录
    public function examineList(Request $request){
        $params = $request->input();
        $re = (new CashApply())->examine_list($params);
        //查询所有站点
        $site=DB::table('site_3e21')->where('siteid','<>',1)->select('siteid','site_name')->get();

        return view("Admin.capital.examine",compact("re","site"));
    }

    //提现拒绝审核
    public function examineNo(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute=(new CashApply())->examine_no($params);
            return $execute;
        }
        $data['cash_id']=$request->input('id');
        return view('Admin.capital.no',compact('data'));
    }

    //允许提现
    public function examineYes(Request $request){
        $id=$request->input('id');
        $execute=(new CashApply())->examine_yes(array('cash_id'=>$id));
        return $execute;
    }


}