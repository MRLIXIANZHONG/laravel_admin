<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BaseModel;
use App\Models\SystemLog;
use App\Models\Iprecording;

class SystemController extends BaseController{

    /**
     * 系统redis重置
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function redisRenew(Request $request){
        if($request->ajax()){
            $execute = (new BaseModel)->redisRenew();
            return $execute;
        }else{
            return view("Admin.system.redisRenew");
        }
    }

    /**
     * 日志列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function systemLogIndex(Request $request)
    {
        $params = $request->input();
        $list = (new SystemLog())->search($params);
        return view('Admin.systemLog.index',compact('list'));
    }

    /**
     * 日志查看
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function systemLogShow(Request $request)
    {
        $info = (new SystemLog)->find($request->input('id'));
        if ($info) {
            return view('Admin.systemLog.show',compact('info'));
        } else {
            return redirect('Custom_throw?error=该信息获取失败，请刷新页面后重试！');
        }
    }

    /**
     * IP记录
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ipRecordIndex(Request $request){
        $params = $request->input();
        $list = (new Iprecording)->search($params);
        return view('Admin.systemIpRecord.index',compact('list'));
    }

    /**
     * IP详情
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function ipRecordShow(Request $request){
        $info = (new Iprecording)->find($request->input('id'));
        if ($info) {
            return view('Admin.systemIpRecord.show',compact('info'));
        } else {
            return redirect('Custom_throw?error=该信息获取失败，请刷新页面后重试！');
        }
    }

    /**
     * 用户状态修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ipRecordEditType(Request $request){
        $id = $request->input('id');
        $execute = (new Iprecording)->ipRecordEditType($id);
        return $execute;
    }
}
