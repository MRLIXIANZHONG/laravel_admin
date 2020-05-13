<?php

namespace App\Http\Controllers\Admin;

use App\Models\CashApply;
use App\Models\Sms;
use App\Models\TWithdrawalsLog;
use App\Models\WechatConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class CapitalController
 * @package App\Http\Controllers\Admin
 * 前端会员提现管理
 */
class UserCarryController extends BaseController{


    //查询会员提现记录
    public function index(Request $request){
        $params = $request->input();
        $re = (new TWithdrawalsLog())->search($params);
        return view("Admin.usercarry.index",compact("re"));
    }

    //查询会员提现弹窗
    public function popup(Request $request){
        $params = $request->input();
        $data = (new TWithdrawalsLog())->popup($params);
        $re['statue']='正常';
        if ($data){
            //查询用户流水是否正常
            $sprice= DB::table('T_PayOrder_User')->where('uid',$data->UserID)->sum('SPrice');
            if ($sprice != $data->balance){
                $re['statue']='不正常';
            }
        }

        return view("Admin.usercarry.popup",compact("re",'data'));

    }

    //会员提现操作
    public function operation(Request $request){
        $params = $request->input();
        $execute = (new TWithdrawalsLog())->operation($params);
        return $execute;
    }

    //查询会员流水
    public function water(Request $request){
        $params = $request->input();
        $re = (new TWithdrawalsLog())->water($params);
        return view("Admin.usercarry.water",compact("re"));
    }



}