<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.blank')
@section('title','权限管理')
@section('breadcrumb')
    <li><a href="{{url('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">权限管理</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                </div>
                <div class="box-body">
                    <form method="get" class="forms-sample"  target="search_iframe">
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索名称" name="display_name" @if(!empty($_GET['display_name'])) value="{{$_GET['display_name']}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索路由" name="name" @if(!empty($_GET['name'])) value="{{$_GET['name'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="type" id="type">
                                <option value="">全部权限</option>
                                    <option value="unnamed" @if(!empty($_GET['type']) and $_GET['type']=='unnamed')
                                    selected="selected" @endif>尚未命名的</option>
                                    <option value="named" @if(!empty($_GET['type']) and $_GET['type']=='named')
                                selected="selected" @endif>已命名的</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/permission/route/index')}}" class="btn btn-default pull-right">
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
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 权限列表/检索权限:<span
                            id="data-num">{!!$lists->total()!!}</span>条</h3>
                    <div class="pull-right mt10">
{{--                        <a href="javascript:void(0)" onClick="routeAdd('{{url('admin/permission/route/create')}}')"--}}
{{--                           class="btn btn-sm btn-primary">添加权限[权限]</a>--}}
                        @if(getAuth()->can('admin/permission/route/check'))
                        <a href="javascript:void(0)" onClick="routeCheck()" class="btn btn-sm btn-primary">检测路由[权限]</a>
                        @endif
                        </div>
                    </div>
                    <div class="box-body" id="data-list">
                        @if($lists->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>guard</th>
                                <th>名称</th>
                                <th>描述</th>
                                <th>路由</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$v->guard_name}}</td>
                                    <td>{{$v->display_name ?: '暂未定义'}}</td>
                                    <td>{{$v->description ?: '暂无描述'}}</td>
                                    <td>{{$v->name}}</td>
                                    <td>
                                        @if(getAuth()->can('admin/permission/route/update'))
                                        <button type="button" class="btn btn-info" title="修改" onClick="edit_permission('{{url('admin/permission/route/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                        @endif
                                        @if(getAuth()->can('admin/permission/route/delete'))
                                        <button type="button" class="btn btn-danger" title="删除" onClick="deletePermission({{$v->id}})"><i class="fa fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6">
                                    {!! $lists->render('Admin.base.custom') !!}
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
            //弹窗1
            function routeAdd(url){
                layer.open({
                    type:2,           //类型，解析url
                    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                    title:"权限添加",
                    shadeClose: false, //点击遮罩区域是否关闭页面
                    shade: 0.6,       //遮罩透明度
                    area:["50%","45%"],
                    content:url
                })
            }
            //路由权限信息修改
            function edit_permission(url) {
                layer.open({
                    type:2,           //类型，解析url
                    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                    title:"权限修改",
                    shadeClose: false, //点击遮罩区域是否关闭页面
                    shade: 0.6,       //遮罩透明度
                    area:["50%","45%"],
                    content:url
                })
            }
            //权限删除
            function deletePermission(id) {
                layer.confirm('确定删除该权限?',{btn:['确定','取消']},function () {
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                    $.ajax({
                        type : 'get',
                        url: "{{url('admin/permission/route/delete')}}",
                        data:{id:id},
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
            //路由检测
            function routeCheck() {
	            layer.confirm('确定检测路由[系统会自动添加未注册的路由于权限列表中]?',{btn:['确定','取消']},function () {
		            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		            $.ajax({
			            type : 'get',
			            url: "{{url('admin/permission/route/check')}}",
			            dataType:"json",
			            success:function (datas) {
				            if(datas['code']===200){
                                if(datas['data']){
                                	var info;
	                                info = datas['data'].join("<br />");
	                                layer.confirm(
                                        '<font style="color: red">'
                                        +datas['infor']+'</font>'
                                        +"<br />"+info
                                        +'<br /><b>是否需要删除以上路由？</b>',
                                        {btn:['确定','取消']}, function () {
		                                $.ajax({
			                                type : 'get',
			                                url: "{{url('admin/permission/route/check')}}",
                                            method: 'put',
			                                dataType:"json",
			                                success:function (datas) {
				                                if(datas['code']===200){
					                                layer.msg(datas['infor'], {time:3000},function () {
						                                parent.window.location.reload();
					                                })
				                                }else{
					                                layer.msg(datas['infor'],{time:4000})
				                                }
			                                }
		                                });
	                                });
                                }else{
	                                layer.msg(datas['infor'], {time:2000},function () {
		                                parent.window.location.reload();
	                                })
                                }
				            }else{
					            layer.msg(datas['infor'],{time:2000})
				            }
			            }
		            });
	            });
            }
        </script>
    @endsection

