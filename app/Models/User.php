<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */

namespace App\Models;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as AuthUser;

//laravel伪删除类

class User extends AuthUser {
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    protected $guard_name = 'web';
    protected $table = 'users';
    protected $primaryKey="id";//关键字
    public $timestamps=false;
    protected $guarded=[];
    protected $dates=['deleted_at'];//软删除字段,记录删除时间

    protected $hidden = [
        'password', 'login_token', 'deleted_at',
    ];

    const SUPERADMIN = 1;       //超管id[聚咖盟siteid也用这个常量]

    const TYPEACTIVITY = 1;
    const TYPEDISABLE = 2;
    public $typeLabel = [self::TYPEACTIVITY => '正常', self::TYPEDISABLE => '禁用',];
    public function getTypeTextAttribute(){
        return isset($this->typeLabel[$this->type]) ? $this->typeLabel[$this->type] : $this->type;
    }

    //获取盟友站点信息
    public function site3E21()
    {
        return $this->belongsTo('App\Models\Site3E21','siteid','siteid');
    }

    /**
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params){
        $query = $this;
        if(!empty($params['username'])){
            $query = $query->where('username','like','%'.$params['username'].'%');
        }
        if (!empty($params['name'])) {
            $query = $query->where('name','like', '%'.$params['name'].'%');
        }
        if (!empty($params['email'])) {
            $query = $query->where('email','like', '%'.$params['email'].'%');
        }
        if (!empty($params['siteid'])) {
            $query = $query->where('siteid',$params['siteid']);
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->orderBy('id', 'desc')->paginate($limit);
        $appendData = $re->appends(array(
            'username' => @$params['username'],
            'name' => @$params['name'],
            'email' => @$params['email'],
            'limit' => @$params['limit'],
            'siteid' => @$params['siteid'],
        ));
        return $re;
    }

    /**
     * 根据用户id 获取用户信息
     * Created by Lxd
     * @param $id
     * @return bool
     */
    public function getUserInfoById($id){
        if($info = $this->where('id',$id)->first()){
            return $info->toArray();
        }
        return false;
    }

    /**
     * 根据用户email获取邮箱
     * Created by Lxd
     * @param $email
     * @return bool
     */
    public function getUserInfoByEmail($email){
        if($info = $this->where('email',$email)->first()){
            return $info->toArray();
        }
        return false;
    }

    /**
     * 根据用户名/邮箱 获取用户信息
     * Created by Lxd
     * @param $user
     * @return mixed
     */
    public function getUserInfoOnlogin($user){
        if(!$info = $this->where('username',$user)->first()){
            $info = $this->where('email',$user)->first();
        }
        return $info;
    }

    /**
     * 用户登录token更新
     * Created by Lxd
     * @param $id
     * @param $token
     * @return mixed
     */
    public function upUserToken($id,$token){
        return $this->where('id',$id)->update(['login_token' => $token]);
    }

    /**
     * 用户登录
     * Created by Lxd
     * @param $params
     * @return bool|string
     */
    public function UserLogin($params){
        $userInfo = $this->getUserInfoOnlogin($params["user"]);
        if ($userInfo) {
            if(!isset($userInfo->getRoleNames()[0])){
                return response()->json(['code'=>302,'infor'=>'该账户尚未绑定角色身份']);
            }
            if ($userInfo->type !== self::TYPEACTIVITY) {
                return response()->json(['code'=>302,'infor'=>'你的账号被超管禁用了']);
            }
            if (!$userInfo->siteid){
                return response()->json(['code'=>302,'infor'=>'您的账户尚未绑定站点']);
            }
            $pass1 = $userInfo->password;
            if ($params["password"] == $pass1) {
                $login_token = md5(time().$params["user"]);
                $this->where('id',$userInfo->id)->update(['login_token' => $login_token]);
                Auth::guard('admin_user')->login($userInfo);
                return response()->json(['code'=>200,'infor'=>'登录成功,即将跳转首页']);
            } else {
                return response()->json(['code'=>302,'infor'=>'密码错误']);
            }
        } else {
            return response()->json(['code'=>302,'infor'=>'你输入的用户不存在,重新验证一下']);
        }
    }

    public function UserLoginOut(){
        Auth::guard('admin_user')->logout();
    }

    /**
     * 用户信息修改
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function updateUser($params,$role){
        $validatorhelp = $this->Validator_UserModel($params,'up');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        if($role !== false) {                                 //用户列表修改信息
            $this->find($params['id'])->syncRoles($role);     //角色重新赋予
        }else{                                                //修改个人信息
            if(!empty($params['pwd'])){
                if(strlen($params['pwd']) < 6)
                    return response()->json(['code' =>456, 'infor' => '密码必须大于等于6位长度']);
                if($params['pwd'] !== $params['repwd'])
                    return response()->json(['code' =>456, 'infor' => '两次密码输入不一致,请确认后输入提交']);
                $params['password'] = $params['pwd'];
            }
        }
        unset($params['pwd']);unset($params['repwd']);
        $roleUpdate = $role ?: null;
        $params['updated_at'] = getDateTime();
        if($this->where('id',$params['id'])->update($params) || $roleUpdate){
            if(!isset($params['password'])) {
                return response()->json(['code' => 200, 'infor' => '用户基础信息更新成功']);
            }else{
                //return response()->json(['code' => 200, 'infor' => '更新成功,即将跳转登录页']);
                return redirect('admin/quit');
            }
        }
        return response()->json(['code' => 302, 'infor' => '无数据变更',]);
    }

    /**
     * 头像修改
     * Created by Lxd
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAvatar($file){
        $upFile = (new UploadFile)->UpdateImage($file,200,200);
        if ($upFile['code'] != 200) {
            return response()->json(['code'=>456,'infor'=>$upFile['msg']]);
        }
        if($this->where('id',getAuth()->id)->update(['avatar'=>$upFile['data']])){
            return response()->json(['code'=>200,'infor'=>"头像修改成功"]);
        }
        return response()->json(['code'=>302,'infor'=>"网络异常,修改失败"]);
    }

    /**
     * 添加用户
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function insertUser($params,$role){
        $params['password'] = $params['password'] ?: '123456';
        $validatorhelp = $this->Validator_UserModel($params,'insert');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        //$params['password'] = Crypt::encryptString($params['password']);
        $params['created_at'] = getDateTime();
        //看是否是联盟管理员
        if ($params['role']=='联盟管理员'){
            $params['is_admin']=1;//是管理员
        }
        if($insertId = $this->insertGetId($params)){
            $this->find($insertId)->assignRole($role);      //角色赋予
            return response()->json(['code' =>200, 'infor' => '用户添加成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);
    }

    /**
     * 用户删除
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id){
        $idArr = explode(',',$id);
        foreach ($idArr as $v){
            $this->where('id',$v)->delete();
        }
        $infor = count($idArr) > 1 ? "批量删除用户成功" : "删除用户成功";
        return response()->json(['code' => 200, 'infor' => $infor]);
    }

    /**
     * 管理员密码重置
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function reserPassword($params){
        $info = $this->find($params['id']);

        if(!$info){
            return response()->json(['code'=>456,'infor'=>"管理员信息获取失败"]);
        }
        if($params['firstpwd'] !== $params['secondpwd']){
            return response()->json(['code'=>456,'infor'=>"两次密码输入不一样,请重新输入"]);
        }
        $data['password'] = $params['firstpwd'];
        $validatorhelp = $this->Validator_UserModel($data);
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        //$data['password'] = Crypt::encryptString($data['password']);
        if($this->where('id',$info->id)->update(['password'=>$data['password']])){
            return response()->json(['code'=>200,'infor'=>"更新管理员管理员名为[{$info->username}]的密码成功"]);
        }
        return response()->json(['code'=>302,'infor'=>"网路异常"]);
    }

    /**
     * 用户状态修改
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function typeUpdate($id){
        $userInfo = $this->find($id);
        if(!$userInfo){
            return response()->json(['code' =>456, 'infor' => '用户不存在']);
        }
        $userInfo->type = $userInfo->type == self::TYPEACTIVITY ? self::TYPEDISABLE : self::TYPEACTIVITY;
        $userInfo->save();
        return response()->json(['code' => 200, 'infor' => '用户状态修改成功']);
    }

    /**
     * 重置密码token获取
     * Created by Lxd
     * @param $userInfo
     * @return string
     */
    public function getResetPwdToken($userInfo){
        return Crypt::encryptString($userInfo['id']."/".$userInfo['email']."/".time());
    }

    /**
     * 重置密码token验证
     * Created by Lxd
     * @param $token
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkResetPwdToken($token){
        try {
            $tokenArr = explode('/', Crypt::decryptString($token));
            if (@(time() - $tokenArr[2]) >= 600) {
                return redirect('Custom_throw?error=邮件有效期已过[10分钟],请重新发送邮件生成验证链接');
            }
            if ($this->where(['id' => $tokenArr[0], 'email' => $tokenArr[1]])->count()) {
                return $tokenArr[1];
            }
            return redirect('Custom_throw?error=用户邮箱验证失败,请重新发送邮件生成验证链接');
        }catch (\Exception $e){
            return redirect('Custom_throw?errot=TOKEN验证失败,请重新发送邮件生成验证链接');
        }
    }

    /**
     * 重置密码执行
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function resetPwd($params){
        if($userInfo = $this->where('email',$params['email'])->first()){
            $params['id'] = $userInfo->id;
            $validatorhelp = $this->Validator_UserModel($params,'up');
            if($validatorhelp !== true){
                return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
            }
            if($params['password'] !== $params['repassword']){
                return response()->json(['code' => 302, 'infor' => "两次输入密码不一致,请重新输入",]);
            }
            if($params['password'] == $userInfo->password){
                return response()->json(['code' => 302, 'infor' => "本次输入密码与原密码相同,请重新定义一个新密码",]);
            }
            if($this->where('email',$params['email'])->update(['password'=>$params['password']])){
                return response()->json(['code' => 200, 'infor' => "密码修改成功,即将跳转登录页",]);
            }
            return response()->json(['code' => 456, 'infor' => "网络异常,修改密码失败",]);
        }
        return response()->json(['code' => 404, 'infor' => "你的邮箱{$params['email']}尚未在本系统注册",]);
    }

    /**
     * 用户微信绑定
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function wechatBind($params)
    {
        $info = self::find($params['uid']);
        $info->openid = $params['openid'];
        $info->nickname = $params['nickname'];
        $info->avatar_wechat = $params['avatar_wechat'];
        if($info->save()){
            return ['code' => 200, 'infor' => '绑定成功'];
        }else{
            return ['code' => 456, 'infor' => '网络异常,绑定失败'];
        }
    }

    /**
     * 用户表单
     * Created by Lxd
     * @param $parms
     * @return bool|string
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function Validator_UserModel($parms,$type = 'insert'){
        foreach ($parms as $v){
            if(!is_string($v)) continue;
            if((new BaseModel)->Validator_SensitiveHelper($v,'detect'))
                return '表单含有敏感词内容,请重新输入';
        }
        $rules = [];
        $message = [];
        foreach ($parms as $k=>$v){
            switch ($k){
                case 'username':
                    if($type == 'insert'){
                        $rules['username'] = 'required|allow_letter|unique:users,username';
                    }else{
                        $rules['username'] = [
                            'required','allow_letter',
                            Rule::unique('users')->ignore($parms['id'],'id'),
                        ];
                    }
                    $message['username.required'] = '用户名必须';
                    $message['username.allow_letter'] = '用户名只能由2-16位数字或字母、汉字、下划线组成';
                    $message['username.unique'] = '系统已存在该用户名';break;
                case 'site' :
                    $rules['site'] = 'required|integer';
                    $message['site.required'] = '必须绑定盟友';
                    $message['site.integer'] = 'siteid数据类型错误';break;
                case 'password':
                    $rules['password'] = 'required|between:6,20';
                    $message['password.required'] = '请填写密码';
                    $message['password.between'] = '密码仅允许在6-20位之间';break;
                case 'email':
                    if($type == 'insert'){
                        $rules['email'] = 'required|email|unique:users,email';
                    }else{
                        $rules['email'] = [
                            'required','email',
                            Rule::unique('users')->ignore($parms['id'],'id'),
                        ];
                    }
                    $message['email.required'] = '请输入正确邮箱';
                    $message['email.email'] = '邮箱格式错误';
                    $message['email.unique'] = '系统已存在该邮箱';break;
                case 'name':
                    $rules['name'] = 'required|between:2,20';
                    $message['name.required'] = '姓名必须';
                    $message['name.between'] = '姓名请保持在2-20个字符之间';break;
                case 'type':
                    $rules['type'] = 'required|integer';
                    $message['type.required'] = '用户状态值接收异常';
                    $message['type.integer'] = '用户状态值请保证为integer';break;
            }
        }
        $validator = Validator::make($parms, $rules, $message);
        return $validator->passes() ? true : $validator->errors()->first();
    }
}
