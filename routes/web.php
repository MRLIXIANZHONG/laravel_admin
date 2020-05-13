<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('out-site/wecaht-bind','Admin\IndexController@wechatBind');                 //未使用|用户微信绑定
Route::any('admin/sms/callback','Admin\SmsController@callback');//短信充值回调

Route::group(['middleware' => ['web','operationLog']],function (){
    Route::get('Custom_throw','Admin\OutSideController@Custom_throw');                 //自定义抛出页面
    Route::any('admin/login','Admin\OutSideController@adminlogin');                    //登录
    Route::get('admin/quit', 'Admin\OutSideController@quit');                          //后台退出
    Route::get('admin/directQuit', 'Admin\OutSideController@directQuit');              //后台退出
    Route::any('admin/reset_pwd','Admin\OutSideController@resetPwd');                  //重置密码
    Route::any('admin/execute/reset_pwd','Admin\OutSideController@executeReserPwd');   //重置密码
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware' => ['web','AdminLogin','operationLog']],function (){
    Route::get('','IndexController@index');                             //首页
    Route::any('myself','UserController@myself');                       //个人中心
    Route::any('edit_avatar','UserController@editAvatar');              //个人头像修改

    Route::get('permission/route/index','PermissionController@routeIndex');         //权限管理
    Route::any('permission/route/check','PermissionController@routeCheck');         //权限检测
    Route::any('permission/route/create','PermissionController@routeCreate');       //权限权限添加
    Route::any('permission/route/update','PermissionController@routeUpdate');       //权限权限修改
    Route::delete('permission/route/delete','PermissionController@routeDelete');    //权限权限删除

    Route::get('permission/group/index','PermissionController@groupIndex');         //权限组管理
    Route::get('permission/group/list','PermissionController@groupList');           //权限组列表
    Route::any('permission/group/create','PermissionController@groupCreate');       //权限组添加
    Route::any('permission/group/update','PermissionController@groupUpdate');       //权限组修改
    Route::delete('permission/group/delete','PermissionController@groupDelete');    //权限组删除
    Route::any('permission/group/moveout','PermissionController@groupMoveout');     //权限移出组
    Route::any('permission/group/movein','PermissionController@groupMovein');       //权限移入组

    Route::get('permission/role/index','PermissionController@roleIndex');           //角色管理
    Route::get('permission/role/list','PermissionController@roleList');             //角色列表
    Route::any('permission/role/create','PermissionController@roleCreate');         //角色添加
    Route::any('permission/role/update','PermissionController@roleUpdate');         //角色修改
    Route::delete('permission/role/delete','PermissionController@roleDelete');      //角色删除
    Route::put('permission/role/binding','PermissionController@roleBinding');       //角色权限绑定
    Route::get('permission/role/initialize','PermissionController@roleInitialize'); //超管权限初始化

    Route::get('permission/navigation/index','PermissionController@navigationIndex');       //导航管理
    Route::any('permission/navigation/create','PermissionController@navigationCreate');     //导航添加
    Route::any('permission/navigation/update','PermissionController@navigationUpdate');     //导航修改
    Route::delete('permission/navigation/delete','PermissionController@navigationDelete');  //导航删除

    Route::get('user/index','UserController@index');                   //用户列表页
    Route::any('user/create','UserController@create');                 //用户添加
    Route::any('user/update','UserController@update');                 //用户修改
    Route::delete('user/delete','UserController@delete');              //用户删除
    Route::any('user/reset_psword','UserController@reserPassword');    //用户密码重置
    Route::put('user/type/update','UserController@typeUpdate');        //用户状态修改

    Route::any('system-redis/renew','SystemController@redisRenew');             //redis更新
    Route::get('system-log/index','SystemController@systemLogIndex');           //日志管理
    Route::any('system-log/show','SystemController@systemLogShow');             //日志查看
    Route::get('system-iprecord/index','SystemController@ipRecordIndex');       //ip访问记录
    Route::any('system-iprecord/show','SystemController@ipRecordShow');         //ip记录详情
    Route::put('system-iprecord/edit_type','SystemController@ipRecordEditType');//修改IP状态



    Route::get('wechat/bank/index','WechatController@BankIndex');               //支付配置管理
    Route::any('wechat/bank/create','WechatController@BankCreate');             //支付配置添加
    Route::any('wechat/bank/update','WechatController@BankUpdate');             //支付配置修改
    Route::any('wechat/system/index','WechatController@wechatIndex');           //更新微信配置
    Route::get('site/index','SiteController@index');                            //站点列表
    Route::any('site/create','SiteController@create');                          //站点添加
    Route::any('site/update','SiteController@update');                          //站点修改
    Route::get('site/show','SiteController@show');                              //站点查看
    Route::delete('site/delete','SiteController@delete');                       //站点删除
    Route::get('get-site','SiteController@getSite');                            //站点列表获取|待接入
    Route::get('get-wechat-bind','SiteController@getWechatBind');               //获取绑定结果
    Route::any('site/wechat-bind','SiteController@wechatBind');
    Route::get('get-area','SiteController@getArea');                            //区域获取|待接入

    Route::get('company/index','CompanyController@index');                      //公司列表
    Route::any('company/create','CompanyController@create');                    //公司添加
    Route::any('company/update','CompanyController@update');                    //公司修改
    Route::delete('company/delete','CompanyController@delete');                 //公司删除
    Route::get('get-company','CompanyController@getCompany');                   //站点列表获取|待接入

    Route::get('payorder-comp/index','PayOrderCompController@index');           //站点账户列表
    Route::get('payorder-comp/show-list','PayOrderCompController@showList');    //站点账户收入列表
    Route::get('payorder-comp/show-detail','PayOrderCompController@showDetail');//账户指定订单详情查看


    //短信管理
    Route::get('sms/index','SmsController@index');//短信列表
    Route::any('sms/create','SmsController@create');//添加短信商
    Route::any('sms/recharge','SmsController@recharge');//短信充值
    Route::any('sms/delete','SmsController@delete');//短信充值
    Route::any('sms/smslog','SmsController@smsLog');//短信日志
    Route::any('sms/smsrl','SmsController@smsRechargeLog');//短信充值日志

    //账户余额管理
    Route::any('capital/index','CapitalController@index');//余额列表
    Route::any('capital/carry','CapitalController@carry');//余额提现申请
    Route::any('capital/examinelist','CapitalController@examineList');//余额提现记录
    Route::any('capital/examineNo','CapitalController@examineNo');//拒绝审核
    Route::any('capital/examineYes','CapitalController@examineYes');//审核通过


    //用户提现
    Route::any('usercarry/index','UserCarryController@index');//用户提现列表
    Route::any('usercarry/popup','UserCarryController@popup');//用户提现操作弹窗
    Route::any('usercarry/operation','UserCarryController@operation');//用户提现操作
    Route::any('usercarry/water','UserCarryController@water');//用户提现流水

    //二级域名配置
    Route::get('site/site_level_index','SiteController@site_level_index');//获取二级域名列表
    Route::any('site/site_level_add','SiteController@site_level_add');//添加二级域名
    Route::any('site/site_level_edit','SiteController@site_level_edit');//修改二级域名列表
    Route::delete('site/site_level_del','SiteController@site_level_del');//删除二级域名列表


});
