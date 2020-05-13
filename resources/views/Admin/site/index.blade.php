<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\User;
$getAuth = getAuth();
?>
@extends('Admin.base.blank')
@section('title','站点配置')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">站点配置</li>
@endsection
@section('content')
    @if($getAuth->siteid == User::SUPERADMIN)
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                    </div>
                    <div class="box-body">
                        <form method="get" class="forms-sample"  target="search_iframe">
                            <div class="form-group col-md-2">
                                <input type="text" class="form-control search-btn" placeholder="搜索站点名" name="site_name"
                                       @if(!empty($_GET['site_name'])) value="{{$_GET['site_name']}}" @endif>
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" class="form-control search-btn" placeholder="搜索站点城市类型"
                                       name="city_type"
                                       @if(!empty($_GET['city_type'])) value="{{$_GET['city_type'] ?: ''}}" @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" class="form-control search-btn" placeholder="搜索站点城市名"
                                       name="areatitle"
                                       @if(!empty($_GET['areatitle'])) value="{{$_GET['areatitle'] ?: ''}}" @endif>
                            </div>
                            <div class="form-group col-md-2">
                                <select class="form-control search-select" name="limit" id="limit">
                                    <option value="10">显示条数(10)</option>
                                    @foreach(getSizeArr() as $v)
                                        <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="{{url('admin/site/index')}}" class="btn btn-default pull-right">
                                <i class="fa fa-trash-o"></i>清空检索条件
                            </a>
                            <div style="display: none;">
                                <input type="submit" class="btn btn-primary" value="搜索">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-weixin"></i>微信绑定信息</h3>
                        <div class="box-tools pull-right">
                            <a href="javascript:void(0)" onClick="wechatBind('{{url('admin/site/wechat-bind')}}')"
                               class="btn btn-sm btn-primary">{{$getAuth->openid ? '微信换绑' : '绑定微信'}}</a>
                        </div>
                    </div>
                    <div class="box-body">
                        @if($getAuth->openid)
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>openid</th>
                                    <th>昵称</th>
                                    <th>头像地址</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{$getAuth->openid}}</td>
                                    <td>{{$getAuth->nickname}}</td>
                                    <td class="user-block">
                                        <a href="{{asset($getAuth->avatar_wechat)}}" target="_blank">点击跳转查看</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>404!</strong> 暂未绑定微信,请点击绑定微信按钮进行绑定操作.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$re->total()!!}</span>条</h3>
                    <div class="pull-right mt10">
                        @if($getAuth->can('admin/site/create'))
                            <a href="javascript:void(0)" onClick="dataAdd('{{url('admin/site/create')}}')"
                               class="btn btn-sm btn-primary">添加站点</a>
                        @endif
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>站点名</th>
                                <th>站点地址</th>
                                <th>站点城市类型</th>
                                <th>站点城市名</th>
                                <th>简介文本</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>
                                        @if($getAuth->can('admin/user/index'))
                                            <a href="{{url("admin/user/index?siteid={$v->siteid}")}}" title="点击跳转查看该站点的后台管理员列表">{{str_limit($v->site_name,25)}}</a>
                                        @else
                                            {{str_limit($v->site_name,25)}}
                                        @endif
                                    </td>
                                    <td>{{str_limit($v->weburl,25)}}</td>
                                    <td>{{str_limit($v->city_type,25)}}</td>
                                    <td>{{str_limit($v->areatitle,25)}}</td>
                                    <td>{{str_limit($v->logotxt,25)}}</td>
                                    <td>
                                        @if($getAuth->can('admin/site/show'))
                                            <button type="button" class="btn btn-primary" title="查看详情" onClick="dataShow('{{url('admin/site/show?siteid='.$v->siteid)}}')"><i class="fa fa-eye"></i></button>
                                        @endif
                                        @if($getAuth->can('admin/site/update'))
                                            <button type="button" class="btn btn-info" title="修改" onClick="dataEdit('{{url('admin/site/update?siteid='.$v->siteid)}}')"><i class="fa fa-edit"></i></button>
                                        @endif
                                        @if($getAuth->can('admin/site/delete'))
                                            <button type="button" class="btn btn-danger" title="销毁站点"
                                                    onClick="dataDelete({{$v->siteid}})"><i class="fa fa-trash"></i></button>
                                        @endif
                                            <button type="button" class="btn btn-success" title="业务系统配置"
                                                    onClick="dataShow2('{{url('admin/site/site_level_index?siteid='.$v->siteid)}}')"><i class="fa fa-eye"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6">
                                    {!! $re->render('Admin.base.custom') !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>404!</strong> 当前列表暂无数据.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <iframe id="search_iframe" name="search_iframe" style="display:none;"></iframe>
@endsection
@section('script')
    <script src="{{asset('lib/admin/admin.automatic.search.js')}}"></script>
    <script>
        //查看二级域名
        function dataShow2(url) {
           location.href=url;
        }

	    //信息查看
	    function dataShow(url) {
		    layer.open({
			    type:2,           //类型，解析url
			    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
			    title:"信息查看",
			    shadeClose: false, //点击遮罩区域是否关闭页面
			    shade: 0.6,       //遮罩透明度
			    area:["80%","80%"],
			    content:url
		    })
	    }
        //站点添加
        function dataAdd(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"站点添加",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["80%","80%"],
                content:url
            })
        }
        //信息修改
        function dataEdit(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"信息修改",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["80%","80%"],
                content:url
            })
        }
        //微信扫码绑定
        function wechatBind(url) {
	        layer.confirm('如果您绑定过微信,重新进行绑定的话,以前绑定的微信会解绑,是否确定?',{btn:['确定','取消']},function () {
                layer.open({
                    type:2,           //类型，解析url
                    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                    title:"微信扫码绑定",
                    shadeClose: false, //点击遮罩区域是否关闭页面
                    shade: 0.6,       //遮罩透明度
                    area:['250px','300px'],
                    content:url
                })
            });
        }
        //站点删除
        function dataDelete(siteid) {
            layer.confirm('确定删除该站点?',{btn:['确定','取消']},function () {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    type : 'get',
                    url: "{{url('admin/site/delete')}}",
                    data:{siteid:siteid},
                    method:'delete',
                    dataType:"json",
                    success:function (datas) {
                        if(datas['code']===200){
                            layer.msg(datas['infor'], {time:2000},function () {
                                parent.window.location.reload();
                            })
                        }else{
                            layer.msg(datas['infor'],{time:2000})
                        }
                    }
                });
            });
        }
    </script>
@endsection
