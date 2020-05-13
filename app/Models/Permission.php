<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as Spermission;

class Permission extends BaseModel{
    protected $guard_name = 'web';
    const PGNO = 0;             //未分组权限pg_id
    const BASUCSGROUP = 1;      //基础权限[路由]组
    const SUPERADMINROLE = 1;   //超管角色id

    /**
     * 权限缓存清除
     */
    public function cache(){
        app()['cache']->forget('spatie.permission.cache');
    }

    /**
     * 权限[路由]分页查询
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function PermissionSearch($params){
        $query = (new Spermission);
        if(!empty($params['name'])){
            $query = $query->where('name','like','%'.$params['name'].'%');
        }
        if (!empty($params['display_name'])) {
            $query = $query->where('display_name','like', '%'.$params['display_name'].'%');
        }
        if (isset($params['pg_id'])) {
            $query = $query->where('pg_id',$params['pg_id']);
        }
        if (isset($params['type'])) {
            switch ($params['type']){
                case 'unnamed' : $query = $query->whereNull('display_name');break;
                case 'named' : $query = $query->whereNotNull('display_name');break;
            }
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->orderBy('name', 'asc')->paginate($limit);
        $appendData = $re->appends(array(
            'name' => @$params['name'],
            'pg_id' => @$params['pg_id'],
            'display_name' => @$params['display_name'],
            'type' => @$params['type'],
            'limit' => @$params['limit'],
        ));
        return $re;
    }

    /**
     * 获取一级权限[路由]
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function PermissionGet($params){
        $query = (new Spermission);
        if(!empty($params['name'])){
            $query = $query->where('name','like','%'.$params['name'].'%');
        }
        if (!empty($params['display_name'])) {
            $query = $query->where('display_name','like', '%'.$params['display_name'].'%');
        }
        if (isset($params['pg_id'])) {
            $query = $query->where('pg_id',$params['pg_id']);
        }
        //查询
        $re = $query->orderBy('name', 'asc')->get();
        return $re;
    }

    /**
     * 获取一级权限[路由信息]
     * Created by Lxd
     * @param $id
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function getPermissionById($id){
        return Spermission::findById($id);
        //return Spermission::where('id',$id)->first();
    }

    /**
     * 获取一级权限[路由信息]
     * Created by Lxd
     * @param $name
     * @return bool|\Spatie\Permission\Contracts\Permission
     */
    public function getPermissionByName($name){
        try {
            return Spermission::findByName($name);
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 增加权限
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function PermissionCreate($params){
        $validatorhelp = $this->Validator_PermissionModel($params,'insert');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        if(Spermission::create($params)){
            $this->cache();
            return response()->json(['code' =>200, 'infor' => '权限添加成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);
    }

    /**
     * 路由检查
     * Created by Lxd
     * @return \Illuminate\Http\JsonResponse
     */
    public function routeCheck(){
        try {
            $getAllRoutes = $this->getAllRoutes();
            $increase = 0;
            foreach ($getAllRoutes as $value) {
                if (!Spermission::where('name', $value)->count()) {
                    //未注册
                    Spermission::create(['name' => $value]);
                    $increase++;
                }
            }
            $reduce = $this->routeCheckreduce($getAllRoutes);           //检测是否有多余路由
            $this->cache();
            if(empty($reduce)) {
                return response()->json(['code' => 200, 'infor' => "检测完成,新增路由{$increase}条"]);
            }else{
                return response()->json(['code' => 200, 'infor'=> "检测完成,新增路由{$increase}条,另检测到多余路由如下:" , 'data'=>$reduce]);
            }
        }catch (\Exception $e){
            return response()->json(['code' => 500, 'infor' => $e->getMessage()]);
        }
    }

    /**
     * 多余路由检测
     * Created by Lxd
     * @param $allRoutes
     * @return array
     */
    public function routeCheckreduce($allRoutes){
        $permissions = $this->PermissionGet([])->toArray();
        $data = [];
        foreach ($permissions as $value){
            if(!in_array($value['name'],$allRoutes)){
                $data[] = $value['name'];
            }
        }
        return $data;
    }

    /**
     * 权限(路由)删除
     * Created by Lxd
     * @return \Illuminate\Http\JsonResponse
     */
    public function routeReduce(){
        $allRoutes = $this->getAllRoutes();
        $permissions = $this->PermissionGet([])->toArray();
        $reduce = 0;
        foreach ($permissions as $value){
            if(!in_array($value['name'],$allRoutes)){
                $this->PermissionDeleted($value['id']);
                $reduce++;
            }
        }
        return response()->json(['code' => 200, 'infor' => "删除权限成功,共删除{$reduce}条"]);
    }

    /**
     * 修改权限
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function PermissionUpdate($params){
        $validatorhelp = $this->Validator_PermissionModel($params,'update');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        if(Spermission::where('id',$params['id'])->update($params)){
            $this->cache();
            return response()->json(['code' =>200, 'infor' => '权限修改成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,修改失败',]);
    }

    /**
     * 删除权限
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function PermissionDelete($id){
        $idArr = explode(',',$id);
        foreach ($idArr as $v){
            $this->PermissionDeleted($v);
        }
        $infor = count($idArr) > 1 ? "批量删除权限成功" : "删除权限成功";
        $this->cache();
        return response()->json(['code' => 200, 'infor' => $infor]);
    }

    /**
     * 权限删除执行
     * Created by Lxd
     * @param $id
     * @return bool
     */
    public function PermissionDeleted($id){
        Spermission::where('id',$id)->delete();
        DB::table('role_has_permissions')->where('permission_id',$id)->delete();
        DB::table('model_has_permissions')->where('permission_id',$id)->delete();
        DB::table('navigations')->where('permission_id',$id)->delete();
        return true;
    }

    /**
     * 获取所有权限组
     * Created by Lxd
     * @param bool $containBasicsGroup
     * @return \Illuminate\Support\Collection
     */
    public function GroupGetAll($containBasicsGroup = null){
        if($containBasicsGroup)
            return DB::table('permission_groups')->orderBy('id','asc')->get();
        else
            return DB::table('permission_groups')->where('id','<>',self::BASUCSGROUP)->orderBy('id','asc')->get();
    }

    /**
     * 获取权限组内全部权限(路由)
     * Created by Lxd
     * @param $groupsId
     * @return mixed
     */
    public function getGroupPermission($groupsId){
        return Spermission::where('pg_id',$groupsId)->orderBy('id','asc')->get();
    }

    /**
     * 获取权限组信息|根据id
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getGroupById($id){
        return DB::table('permission_groups')->find($id);
    }

    /**
     * 权限组添加
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function GroupCreate($params){
        $validatorhelp = $this->Validator_GroupModel($params,'insert');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        $params['created_at'] = date('Y-m-d H:i:s',time());
        $params['updated_at'] = date('Y-m-d H:i:s',time());
        if(DB::table('permission_groups')->insert($params)){
            $this->cache();
            return response()->json(['code' =>200, 'infor' => '权限组添加成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,添加失败',]);
    }

    /**
     * 权限组修改
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function GroupUpdate($params){
        $validatorhelp = $this->Validator_GroupModel($params,'update');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        $params['updated_at'] = date('Y-m-d H:i:s',time());
        if(DB::table('permission_groups')->where('id',$params['id'])->update($params)){
            $this->cache();
            return response()->json(['code' =>200, 'infor' => '权限组修改成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,修改失败',]);
    }

    /**
     * 权限组删除
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupDelete($id){
        $idArr = explode(',',$id);
        foreach ($idArr as $v){
            $permissionInGroupId = Spermission::where('pg_id',$id)->pluck('id');
            DB::table('role_has_permissions')->whereIn('permission_id',$permissionInGroupId)->delete();
            Spermission::where('pg_id',$id)->update(['pg_id'=>self::PGNO]);
            DB::table('permission_groups')->where('id',$v)->delete();
        }
        $infor = count($idArr) > 1 ? "批量删除权限组成功" : "删除权限组成功,原包含权限已释放";
        $this->cache();
        return response()->json(['code' => 200, 'infor' => $infor]);
    }

    /**
     * 权限移出
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupMoveout($id){
        $idArr = explode(',',$id);
        DB::table('role_has_permissions')->whereIn('permission_id',$idArr)->delete();
        $update = Spermission::whereIn('id',$idArr)->update(['pg_id'=>self::PGNO]);
        if($update){
            $this->cache();
            return response()->json(['code' => 200, 'infor' => "批量移出成功,共移出[{$update}]条数据"]);
        }
        return response()->json(['code' => 456, 'infor' => "网络错误,请稍后再试"]);
    }

    /**
     * 权限移入
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupMovein($params){
        $idArr = explode(',',$params['idArr']);
        if(!DB::table('permission_groups')->where('id',$params['groupId'])->count()){
            return response()->json(['code' => 456, 'infor' => "未找到该组,请刷新后重试"]);
        }
        $update = Spermission::whereIn('id',$idArr)->update(['pg_id'=>$params['groupId']]);
        if($update){
            $allRolesId = Role::pluck('id');
            if($params['groupId'] == self::BASUCSGROUP){
                foreach ($idArr as $k=>$v){
                    foreach ($allRolesId as $key=>$value){
                        $data[($k+1).$key]['permission_id'] = $v;
                        $data[($k+1).$key]['role_id'] = $value;
                    }
                }
                DB::table('role_has_permissions')->insert($data);
            }
            $this->cache();
            return response()->json(['code' => 200, 'infor' => "批量移入成功,共移入[{$update}]条数据"]);
        }
        return response()->json(['code' => 456, 'infor' => "网络错误,请稍后再试"]);
    }

    /**
     * 角色列表|不分页
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function RoleGet($params){
        $query = (new Role);
        if(!empty($params['name'])){
            $query = $query->where('name','like','%'.$params['name'].'%');
        }
        if (!empty($params['description'])) {
            $query = $query->where('description','like', '%'.$params['description'].'%');
        }
        if (isset($params['guard_name'])) {
            $query = $query->where('guard_name',$params['guard_name']);
        }
        //查询
        $re = $query->where('id','<>',self::SUPERADMINROLE)->orderBy('id', 'asc')->get();
        return $re;
    }

    /**
     * 超管权限初始化
     * Created by Lxd
     * @return \Illuminate\Http\JsonResponse
     */
    public function SuperAdminInitialize(){
        $user = User::find(User::SUPERADMIN);
        if(!$user){
            return response()->json(['code' => 302, 'infor' => "未指定超管ID"]);
        }
        try {
            $PermissionGetAll = $this->PermissionGet();
            $role = $this->getRoleById(self::SUPERADMINROLE);
            $user->assignRole($role->name);
            $role->syncPermissions($PermissionGetAll);                //角色权限清除并重新绑定
        }catch (\Exception $exception){
            return response()->json(['code' => 500, 'infor' => "预期之外的异常导致赋权失败"]);
        }
        $this->cache();
        return response()->json(['code' => 200, 'infor' => "超管权限初始化成功[超管:{$user->username}]"]);
    }

    /**
     * 角色添加
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function RoleCreate($params){
        $validatorhelp = $this->Validator_RoleModel($params,'insert');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        if($role = Role::create($params)){
            try {
                //基础权限组权限获取
                $basucsPermission = $this->getGroupPermission(self::BASUCSGROUP)->toArray();
                $basucsPermission = array_column($basucsPermission,'name');
            }catch (\Exception $exception){
                return response()->json(['code' => 404, 'infor' => $exception->getMessage()]);
            }
            $role->syncPermissions($basucsPermission);                //权限赋予
            $this->cache();
            return response()->json(['code' =>200, 'infor' => '角色添加成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);
    }

    /**
     * 获取role[角色信息]
     * Created by Lxd
     * @param $id
     * @return \Spatie\Permission\Contracts\Role
     */
    public function getRoleById($id){
        return Role::findById($id);
    }

    /**
     * 角色修改
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function RoleUpdate($params){
        $validatorhelp = $this->Validator_RoleModel($params,'update');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        if(Role::where('id',$params['id'])->update($params)){
            $this->cache();
            return response()->json(['code' =>200, 'infor' => '角色修改成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,修改失败',]);
    }

    /**
     * 角色删除
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function RoleDelete($id){
        $idArr = explode(',',$id);
        foreach ($idArr as $v){
            Role::where('id',$v)->delete();
            DB::table('role_has_permissions')->where('role_id',$id)->delete();
            DB::table('model_has_roles')->where('role_id',$id)->delete();
        }
        $infor = count($idArr) > 1 ? "批量删除角色成功" : "删除角色成功";
        $this->cache();
        return response()->json(['code' => 200, 'infor' => $infor]);
    }

    /**
     * 获取指定角色全部权限(路由)
     * Created by Lxd
     * @param $roleId
     * @return mixed
     */
    public function getRolePermission($roleId){
        //return Spermission::where('pg_id',$groupsId)->get();
        $role = $this->getRoleById($roleId);
        return $role->permissions;
    }

    /**
     * 角色权限绑定
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function RoleBinding($params){
        try {
            $role = $this->getRoleById($params['id']);
            $basucsPermission = $this->getGroupPermission(self::BASUCSGROUP)->toArray();
        }catch (\Exception $exception){
            return response()->json(['code' => 404, 'infor' => $exception->getMessage()]);
        }
        $permmissionArr = array_merge(array_column($basucsPermission,'name'),$params['permmissionArr']);
        $role->syncPermissions($permmissionArr);                //角色权限清除并重新绑定
        $this->cache();
        return response()->json(['code' => 200, 'infor' => '提交绑定成功']);
    }

    /**
     * permission(路由)表单
     * Created by Lxd
     * @param $parms
     * @param string $type
     * @return bool|string
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function Validator_PermissionModel($parms,$type = 'insert'){
        foreach ($parms as $v){
            if(!is_string($v)) continue;
            if($this->Validator_SensitiveHelper($v,'detect'))
                return '表单含有敏感词内容,请重新输入';
        }
        if(!in_array($parms['name'],$this->getAllRoutes())){
            return '输入的路由地址不存在项目路由中';
        }
        $rules = [];
        $message = [];
        foreach ($parms as $k=>$v){
            switch ($k){
                case 'name':
                    if($type == 'insert'){
                        $rules['name'] = 'required|unique:permissions,name';
                    }else{
                        $rules['name'] = [
                            'required',
                            Rule::unique('permissions')->ignore($parms['id'],'id'),
                        ];
                    }
                    $message['name.required'] = '路由地址必须';
                    $message['name.unique'] = '该路由地址已存在';break;
                case 'display_name':
                    $rules['display_name'] = 'required|between:2,40';
                    $message['display_name.required'] = '请填写名称';
                    $message['display_name.between'] = '名称请在2-40位之间';break;
                case 'icon':
                    $rules['icon'] = 'between:0,20';
                    $message['icon.between'] = 'icon请在0-20位之间';break;
                case 'description':
                    $rules['description'] = 'between:0,400';
                    $message['description.between'] = '权限描述请在0-400字符之间';break;
            }
        }
        $validator = Validator::make($parms, $rules, $message);
        return $validator->passes() ? true : $validator->errors()->first();
    }

    /**
     * permission_groups(路由组)表单
     * Created by Lxd
     * @param $parms
     * @param string $type
     * @return bool|string
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function Validator_GroupModel($parms,$type = 'insert'){
        foreach ($parms as $v){
            if(!is_string($v)) continue;
            if($this->Validator_SensitiveHelper($v,'detect'))
                return '表单含有敏感词内容,请重新输入';
        }
        $rules = [];
        $message = [];
        foreach ($parms as $k=>$v){
            switch ($k){
                case 'name':
                    if($type == 'insert'){
                        $rules['name'] = 'required|between:2,40|unique:permission_groups,name';
                    }else{
                        $rules['name'] = [
                            'required', 'between:2,40',
                            Rule::unique('permission_groups')->ignore($parms['id'],'id'),
                        ];
                    }
                    $message['name.required'] = '权限组名称必须';
                    $message['name.between'] = '权限组名称请在0-40个字符之间';
                    $message['name.unique'] = '该权限组名称已存在';break;
            }
        }
        $validator = Validator::make($parms, $rules, $message);
        return $validator->passes() ? true : $validator->errors()->first();
    }

    /**
     * roles(角色)表单
     * Created by Lxd
     * @param $parms
     * @param string $type
     * @return bool|string
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function Validator_RoleModel($parms,$type = 'insert'){
        if(!empty($parms['name']) && $parms['name'] == '超级管理员'){
            return '名称[超级管理员]为系统保留角色,不可命名';
        }
        foreach ($parms as $v){
            if(!is_string($v)) continue;
            if($this->Validator_SensitiveHelper($v,'detect'))
                return '表单含有敏感词内容,请重新输入';
        }
        $rules = [];
        $message = [];
        foreach ($parms as $k=>$v){
            switch ($k){
                case 'name':
                    if($type == 'insert'){
                        $rules['name'] = 'required|between:2,40|unique:roles,name';
                    }else{
                        $rules['name'] = [
                            'required', 'between:2,40',
                            Rule::unique('roles')->ignore($parms['id'],'id'),
                        ];
                    }
                    $message['name.required'] = '角色名称必须';
                    $message['name.between'] = '角色名称请在0-40个字符之间';
                    $message['name.unique'] = '该角色名称已存在';break;
                case 'description':
                    $rules['description'] = 'between:0,400';
                    $message['description.between'] = '角色描述请在0-400字之间';
            }
        }
        $validator = Validator::make($parms, $rules, $message);
        return $validator->passes() ? true : $validator->errors()->first();

    }
}
