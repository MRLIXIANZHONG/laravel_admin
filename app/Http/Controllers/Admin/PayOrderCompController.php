<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Site3E21;
use App\Models\TPayAgent;
use App\Models\TPayOrderComp;
use App\Models\TPayOrderMain;
use App\Models\TPayType;
use App\Models\User;
use Illuminate\Http\Request;

class PayOrderCompController extends BaseController{

    /**
     * 站点账户列表
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $Model = new Site3E21();
        $params = $request->input();
        //检索到站点列表
        $re = $Model->searchWithAgent($params);
        $siteid = getAuth()->siteid;
        if($siteid == User::SUPERADMIN){
            $count_sum = (new TPayAgent())->getCountSum();
            //超管列表查看
            return view("Admin.payorder-comp.index",compact("re","count_sum"));
        }else{
            //站点首页列表
            return redirect("admin/payorder-comp/show-list?siteid={$siteid}");
        }
    }

    /**
     * 账户列表
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showList(Request $request)
    {
        $Model = new TPayOrderComp();
        $params = $request->input();
        if(!$request->get('siteid') && !@$params['siteid']){
            return redirect('Custom_throw?error=未指定站点');
        }
        $siteid = $request->get('siteid') ?: $params['siteid'];
        $params['siteid'] = $siteid;
        $re = $Model->search($params);
        $where1 = [
            'Isincome'=>TPayOrderComp::ISINCOME_YES,
            'isDel'=>0,
            'state'=>TPayOrderComp::STATE_TREE,
            'Siteid'=>$siteid,
        ];
        $where2 = [
            'Isincome'=>TPayOrderComp::ISINCOME_NO,
            'isDel'=>0,
            'state'=>TPayOrderComp::STATE_TREE,
            'Siteid'=>$siteid,
        ];
        $count_sum['income'] = TPayOrderComp::where($where1)->sum('Price');
        $count_sum['unincome'] = TPayOrderComp::where($where2)->sum('Price');
        $payType = TPayType::where('isdel',0)->get();
        $agent = TPayAgent::where('siteid',$params['siteid'])->first();

        if(getAuth()->siteid == User::SUPERADMIN){
            $site = Site3E21::with('company')->find($params['siteid']);
            return view('Admin.payorder-comp.show-list-admin',compact("re","count_sum","payType","siteid","site","agent"));
        }else{
            return view('Admin.payorder-comp.show-list-other',compact("re","count_sum","payType","siteid","agent"));
        }
    }

    /**
     * 订单详情
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showDetail(Request $request)
    {
        $info = (new TPayOrderMain())->getDetail($request->get('mOrderID'));
        if(!$info){
            return redirect('Custom_throw?error=订单详情获取失败');
        }
        return view("Admin.payorder-comp.show-detail",compact("info"));
    }
}