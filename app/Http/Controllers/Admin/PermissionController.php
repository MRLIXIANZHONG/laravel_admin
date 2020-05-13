<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Navigation;

class PermissionController extends BaseController{

    /**
     * 列表
     * Created by Lxd
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function routeIndex(Request $request){
        $params = $request->input();
        $lists = (new Permission)->PermissionSearch($params);
        return view('Admin.permission.route.index',compact('lists'));
    }

    /**
     * 权限添加/方法暂未使用,改为系统自动注册
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function routeCreate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Permission)->PermissionCreate($params);
            return $execute;
        }
        return view('Admin.permission.route.create');
    }

    /**
     * 路由检测
     * Created by Lxd
     */
    public function routeCheck(Request $request){
        if ($request->isMethod('put')){     //多余路由删除执行
            $execute = (new Permission)->routeReduce();
        }else{                              //路由检测执行
            $execute = (new Permission)->routeCheck();
        }
        return $execute;
    }

    /**
     * 权限修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function routeUpdate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Permission)->PermissionUpdate($params);
            return $execute;
        }
        $id = $request->get('id');
        $permissionInfo = (new Permission)->getPermissionById($id);
        if($permissionInfo)
            return view('Admin.permission.route.update',compact('permissionInfo'));
        else
            return redirect('Custom_throw?error=权限信息获取失败');
    }

    /**
     * 权限删除
     * Created by Lxd
     * @param Request $request
     * @return mixed
     */
    public function routeDelete(Request $request){
        $id = $request->input('id');
        $execute = (new Permission)->PermissionDelete($id);
        return $execute;
    }

    /**
     * 权限组管理
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupIndex(Request $request){
        $params['id'] = isset($request->id) ? $request->id : Permission::BASUCSGROUP;
        $allGroups = (new Permission)->GroupGetAll();
        $groupRoute = (new Permission)->getGroupPermission($params['id']);
        $groupName = @(new Permission)->getGroupById($params['id'])->name ?: '未定义权限组';
        return view('Admin.permission.group.index',compact('allGroups','groupRoute','groupName'));
    }

    /**
     * 权限组列表
     * Created by Lxd
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupList(){
        $allGroups = (new Permission)->GroupGetAll();
        return view('Admin.permission.group.list',compact('allGroups'));
    }

    /**
     * 权限组添加
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function groupCreate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Permission)->GroupCreate($params);
            return $execute;
        }
        return view('Admin.permission.group.create');
    }

    /**
     * 权限组修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function groupUpdate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Permission)->GroupUpdate($params);
            return $execute;
        }
        $id = $request->get('id');
        $groupInfo = (new Permission)->getGroupById($id);
        if($groupInfo)
            return view('Admin.permission.group.update',compact('groupInfo'));
        else
            return redirect('Custom_throw?error=权限组信息获取失败');
    }

    /**
     * 权限组删除
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupDelete(Request $request){
        $id = $request->input('id');
        $execute = (new Permission)->groupDelete($id);
        return $execute;
    }

    /**
     * 权限组移出
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupMoveout(Request $request){
        $idArr = $request->input('idArr');
        $execute = (new Permission)->groupMoveout($idArr);
        return $execute;
    }

    /**
     * 权限组移入
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function groupMovein(Request $request){
        if($request->isMethod('put')){
            $params = $request->input();
            $execute = (new Permission)->groupMovein($params);
            return $execute;
        }
        $groupId = $request->get('groupId');
        $params['pg_id'] = Permission::PGNO;
        $permissionPGNO = (new Permission)->PermissionGet($params);
        if($permissionPGNO)
            return view('Admin.permission.group.movein',compact('permissionPGNO','groupId'));
        else
            return redirect('Custom_throw?error=未定义在组内的权限获取失败');
    }

    /**
     * 角色管理
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function roleIndex(Request $request){
        $params = [];
        $params['id'] = isset($request->id) ? $request->id : null;
        try {
            $roleRoute = isset($request->id) ? (new Permission)->getRolePermission($params['id']) : null;
            $role = isset($request->id) ? (new Permission)->getRoleById($params['id']) : null;
            $lists = (new Permission)->RoleGet($params);
        }catch (\Exception $exception){     //角色被删除
            return redirect('admin/permission/role/index');
        }
        return view('Admin.permission.role.index',compact('lists','roleRoute','role'));
    }

    /**
     * 角色列表
     * Created by Lxd
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function roleList(){
        $params = [];
        $lists = (new Permission)->RoleGet($params);
        return view('Admin.permission.role.list',compact('lists'));
    }

    /**
     * 超管权限初始化
     * Created by Lxd
     * @return \Illuminate\Http\JsonResponse
     */
    public function roleInitialize(){
        $execute = (new Permission)->SuperAdminInitialize();
        return $execute;
    }

    /**
     * 角色添加
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function roleCreate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Permission)->RoleCreate($params);
            return $execute;
        }
        return view('Admin.permission.role.create');
    }

    /**
     * 角色修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function roleUpdate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Permission)->RoleUpdate($params);
            return $execute;
        }
        $id = $request->get('id');
        $dataInfo = (new Permission)->getRoleById($id);
        if($dataInfo)
            return view('Admin.permission.role.update',compact('dataInfo'));
        else
            return redirect('Custom_throw?error=角色信息获取失败');
    }

    /**
     * 角色删除
     * Created by Lxd
     * @param Request $request
     * @return mixed
     */
    public function roleDelete(Request $request){
        $id = $request->input('id');
        $execute = (new Permission)->RoleDelete($id);
        return $execute;
    }

    /**
     * 角色权限绑定
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function roleBinding(Request $request){
        $params = $request->input();
        $execute = (new Permission)->RoleBinding($params);
        return $execute;
    }

    /**
     * 导航管理首页
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function navigationIndex(Request $request){
        $params = $request->input();
        $lists = (new Navigation)->search($params);
        return view('Admin.permission.navigation.index',compact('lists'));
    }

    /**
     * 导航创建
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function navigationCreate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Navigation)->Create($params);
            return $execute;
        }
        $allPermissions = (new Permission)->PermissionGet([]);
        $parent = Navigation::where('parent_id',0)->get();
        return view('Admin.permission.navigation.create',compact('parent','allPermissions'));
    }

    /**
     * 导航修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function navigationUpdate(Request $request){
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $execute = (new Navigation)->NavigationUpdate($params);
            return $execute;
        }
        $id = $request->get('id');
        $infor = (new Navigation)->getNavigationById($id);
        if($infor) {
            $allPermissions = (new Permission)->PermissionGet([]);
            $parent = Navigation::where('parent_id',0)->get();
            return view('Admin.permission.navigation.update', compact('infor','allPermissions','parent'));
        } else {
            return redirect('Custom_throw?error=导航信息获取失败');
        }
    }

    /**
     * 导航删除
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function navigationDelete(Request $request){
        $id = $request->input('id');
        $execute = (new Navigation)->NavigationDelete($id);
        return $execute;
    }
}
