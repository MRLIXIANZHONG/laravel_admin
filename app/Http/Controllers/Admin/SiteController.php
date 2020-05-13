<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use App\Models\Area3E21;
use App\Models\Company;
use App\Models\Site3E21;
use App\Models\SiteLable;
use App\Models\User;
use App\Validate\SiteLevelValidate;
use App\Validate\SiteValidate;
use Illuminate\Http\Request;

class SiteController extends BaseController{

    /**
     * ajax|站点分页获取
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSite(Request $request)
    {
        $params = $request->all();
        $list = (new Site3E21())->getSite($params);
        return response()->json($list);
    }

    public function getArea(Request $request)
    {
        $params = $request->all();
        $list = (new Area3E21())->getArea($params);
        return response()->json($list);
    }

    /**
     * 站点列表页
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $Model = new Site3E21();
        $params = $request->input();
        $re = $Model->search($params);
        return view("Admin.site.index",compact("re"));
    }

    /**
     * 站点创建
     * Created by Lxd
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $validate = SiteValidate::SiteCreate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }
            $execute = (new Site3E21())->add($params);
            return $execute;
        }
        $company = Company::get();
        return view('Admin.site.create',compact('company'));
    }

    /**
     * 站点配置修改
     * Created by Lxd
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');

            $validate = SiteValidate::SiteUpdate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }
            //非超管账户[siteid=1],仅修改部分字段
            if(getAuth()->siteid !== User::SUPERADMIN){
                unset($params['site_name']);
                unset($params['companyno']);
                unset($params['weburl']);
                unset($params['web_dir']);
                unset($params['city_type']);
                unset($params['sfid']);
                unset($params['dsid']);
                unset($params['areano']);
                unset($params['areatitle']);
                unset($params['logo']);
                unset($params['jftime']);
                unset($params['jfendtime']);
                unset($params['linkmemo']);
                unset($params['addUsername']);
                unset($params['spell']);
                unset($params['initial']);
            }
            $execute = (new Site3E21())->edit($params);
            return $execute;
        }
        $siteid = $request->get('siteid');
        $info = Site3E21::find($siteid);
        if($info){
            $company = Company::get();
            return view('Admin.site.update',compact('info','company'));
        }
        else{
            return redirect('Custom_throw?error=信息获取失败');
        }
    }

    /**
     * 站点信息查看
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $siteid = $request->get('siteid');
        $info = Site3E21::find($siteid);
        if($info)
            return view('Admin.site.show',compact('info'));
        else
            return redirect('Custom_throw?error=详情获取失败');
    }

    //站点删除
    public function delete(Request $request)
    {
        $params = $request->all();
        $validate = SiteValidate::dataDelete($params, $msg);
        if (true !== $validate) {
            return response()->json($msg);
        }
        $del = Site3E21::where('siteid',$params['siteid'])->update(['isdel'=>1]);
        if($del)
            return response()->json(['code' => 200, 'infor' => '删除成功']);
        else
            return response()->json(['code' => 456, 'infor' => '网络异常,删除失败']);
    }

    /**
     * 微信绑定页面
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function wechatBind(Request $request)
    {
        User::where('id',getAuth()->id)->update(['openid'=>null,'nickname'=>null,'avatar_wechat'=>null]);
        //$selfUrl = url('out-site/wecaht-bind').'?uid='.getAuth()->id;
        $siteid = getAuth()->siteid;
        $back_url = url('out-site/wecaht-bind').'?uid='.getAuth()->id;
        $redirUrl = "http://auth.hd3360.cn/api/wechat/serve?back_url={$back_url}&siteid={$siteid}&source=18";
        $sideUrl = "http://m.ewm.eccoo.cn/qrimg.ashx?scale=200&codeStr=".urlencode($redirUrl);
        return view('Admin.site.wechat-bind',compact('sideUrl'));
    }

    /**
     * 用户微信绑定接口查询
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWechatBind(Request $request)
    {
        $openid = getAuth()->openid;
        if($openid){
            return response()->json(['code' => 200,'infor'=> '绑定成功,请等待页面自动刷新']);
        }
        return  response()->json(['code' => 456,'infor' => '尚未绑定']);
    }

    //查询下级站点
    public function site_level_index(Request $request){
        $Model = new SiteLable();
        $params = $request->input();
        $re = $Model->get_list($params);
        return view("Admin.site.siteLevel",compact("re"));
    }

    //添加下级站点
    public function site_level_add(Request $request){
        if($request->isMethod('put')){

            $params = $request->except('_method','_token','nm_input_text','nm_submit');

            $validate = SiteLevelValidate::LevelCreate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }
            $execute = (new SiteLable())->level_add($params);
            return $execute;
        }
        $site['siteid']=$request->input('siteid');
        return view('Admin.site.levelAdd',compact('site'));


    }
    //删除下级站点
    public function site_level_del(Request $request){
        $params = $request->all();
        $validate = SiteLevelValidate::dataDelete($params, $msg);
        if (true !== $validate) {
            return response()->json($msg);
        }
        $del = SiteLable::where('id',$params['id'])->update(['isdel'=>1]);
        if($del){
            return response()->json(['code' => 200, 'infor' => '删除成功']);
        }else{
            return response()->json(['code' => 456, 'infor' => '网络异常,删除失败']);
        }
    }
    //编辑下级站点
    public function site_level_edit(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');

            $validate = SiteLevelValidate::LevelUpdate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }
            $execute = (new SiteLable())->edit($params);
            return $execute;
        }
        $id = $request->get('id');
        $info = SiteLable::find($id);
        if($info){
            return view('Admin.site.levelEdit',compact('info'));
        }
        else{
            return redirect('Custom_throw?error=信息获取失败');
        }


    }

}