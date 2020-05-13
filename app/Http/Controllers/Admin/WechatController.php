<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use App\Models\Site3E21;
use App\Models\WechatConfig;
use App\Models\Tpay;
use App\Validate\TpayValidate;
use App\Validate\WechatValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WechatController extends BaseController{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 微信配置
     */
    public function wechatIndex(Request $request)
    {
        if ($request->isMethod('put')){
            //修改
            $params = $request->except('_method','_token','nm_input_text','nm_submit');

            $validate = WechatValidate::WechatUpdate($params, $msg);
            if ($validate !== true) {
                return response()->json(['code' =>201, 'infor' => $msg]);
            }

            DB::table('wechat_config')->update($params);
            return response()->json(['code' =>200, 'infor' => '配置修改成功']);
        }else{
            $detail=WechatConfig::first();
            return view("Admin.wechat.wechat",compact("detail"));
        }


    }

    /**
     * 支付配置列表
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function BankIndex(Request $request)
    {
        $Model = new Tpay();
        $params = $request->input();
        $re = $Model->search($params);
        return view("Admin.wechat.bank-index",compact("re"));
    }

    /**
     * 支付配置添加
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function BankCreate(Request $request)
    {
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $validate = TpayValidate::BankCreate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }
            $execute = (new Tpay())->add($params);
            return response()->json($execute);
        }
        //未完善|需要以ajax分页的方式显示[前端配合]
        $site = Site3E21::where('isdel',0)->get();
        return view('Admin.wechat.bank-create',compact('site'));
    }

    /**
     * 支付配置修改
     * Created by Lxd
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function BankUpdate(Request $request)
    {
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $validate = TpayValidate::BankUpdate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }

            $execute = (new Tpay())->edit($params);
            return response()->json($execute);
        }
        $id = $request->get('id');
        $info = Tpay::find($id);
        if($info)
            return view('Admin.wechat.bank-update',compact('info'));
        else
            return redirect('Custom_throw?error=信息获取失败');
    }
}