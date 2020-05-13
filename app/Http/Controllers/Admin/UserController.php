<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use App\Models\Site3E21;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;

class UserController extends BaseController{

    /**
     * 个人中心
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function myself(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new User)->updateUser($params,false);
            return $execute;
        }
        return view('Admin.user.myself');
    }

    /**
     * 头像修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAvatar(Request $request){
        $file = $request->file('avatar');
        if (!empty($file)) {
            $execute = (new User)->editAvatar($file);
            return $execute;
        } else {
            return response()->json(['code'=>456,'infor'=>'请正确选择图片']);
        }
    }

    /**
     * 用户列表
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index(Request $request){
        $UserModel = new User;
        $params = $request->input();
        $re = $UserModel->search($params);
        return view("Admin.user.index",compact("re"));
    }

    /**
     * 添加用户
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function create(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit','role');
            $execute = (new User)->insertUser($params,$request->input(['role']));
            return $execute;
        }
        $role = (new Permission)->RoleGet([]);
        //未完善|需要以ajax分页的方式显示[前端配合]
        $site = Site3E21::where('isdel',0)->get();
        return view('Admin.user.create',compact('role','site'));
    }

    /**
     * 修改用户
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function update(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit','role');
            $execute = (new User)->updateUser($params,$request->input(['role']));
            return $execute;
        }
        $id = $request->get('id');
        $userInfo = User::find($id);
        $role = (new Permission)->RoleGet([]);
        //未完善|需要以ajax分页的方式显示[前端配合]
        $site = Site3E21::get();
        if($userInfo)
            return view('Admin.user.update',compact('userInfo','role','site'));
        else
            return redirect('Custom_throw?error=用户信息获取失败');
    }

    /**
     * 用户删除|多个以","隔开
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        $id = $request->input('id');
        $execute = (new User)->deleteUser($id);
        return $execute;
    }

    /**
     * 重置用户密码
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function reserPassword(Request $request){
        $params = $request->input();
        $execute = (new User)->reserPassword($params);
        return $execute;
    }

    /**
     * 用户状态修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function typeUpdate(Request $request){
        $params = $request->input();
        $execute = (new User)->typeUpdate($params['id']);
        return $execute;
    }
}
