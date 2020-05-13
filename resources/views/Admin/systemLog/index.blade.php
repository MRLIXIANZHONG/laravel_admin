<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$Permission = new App\Models\Permission;
$PermissionGet = $Permission->PermissionGet([]);
?>
@extends('Admin.base.blank')
@section('title','日志管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">日志管理</li>
@endsection
<link href="{{asset('/lib/laydate/theme/laydate.css')}}">
<script src="{{asset('/lib/laydate/laydate.js')}}"></script>
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
                            <input type="text" id="wlaydate" class="form-control form-control-sm" placeholder="检索时间段" autocomplete="off">
                            <input type="hidden" name="date1" id="date1" @if(!empty($_GET['date1'])) value="{{$_GET['date1']}}" @endif>
                            <input type="hidden" name="date2" id="date2" @if(!empty($_GET['date2'])) value="{{$_GET['date2']}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索操作用户" name="name" @if(isset($_GET['name']) && $_GET['name'] !== '') value="{{ $_GET['name'] }}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索IP" name="ip" @if(isset($_GET['ip']) && $_GET['ip'] !== '') value="{{ $_GET['ip'] }}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="permission" id="permission">
                                <option value="">请求权限(不限)</option>
                                @foreach($PermissionGet as $v)
                                    <option value="{{$v->name}}" @if(!empty($_GET['permission']) and
                                    $_GET['permission']==$v->name)selected="selected" @endif>{{$v->display_name ?: $v->name}}</option>
                                @endforeach
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
                        <a href="{{url('admin/system-log/index')}}" class="btn btn-default pull-right">
                            <i class="fa fa-trash-o"></i>清空检索条件
                        </a>
                        <div style="display: none;">
                            <input type="submit" class="btn btn-primary" value="搜索">
                        </div>
                    </form>
                    <iframe id="search_iframe" name="search_iframe" style="display:none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$list->total()!!}</span>条</h3>
                    <div class="pull-right mt10">
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($list->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>操作用户</th>
                                <th>请求类型</th>
                                <th>IP</th>
                                <th>请求地址:别名</th>
                                <th>请求时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$v->user_id ? $v->user->name : "未登录"}}</td>
                                    <td>{{$v->method}}</td>
                                    <td>{{$v->ip}}:{{$v->ip_info}}</td>
                                    <td>{{$v->path}}:{{$Permission->getPermissionByName($v->path) ?
                                        $Permission->getPermissionByName($v->path)->display_name : '未记录权限'}}</td>
                                    <td>{{$v->created_at}}</td>
                                    <td>
                                        @if(getAuth()->can('admin/system-log/show'))
                                            <button type="button" class="btn btn-info" title="查看更多详情" onClick="showInfo('{{url('admin/system-log/show?id='.$v->id)}}')"><i class="fa fa-eye"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7">
                                    {!! $list->render('Admin.base.custom') !!}
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
@endsection
@section('script')
    <script src="{{asset('lib/admin/admin.automatic.search.js')}}"></script>
    <script src="{{asset('lib/admin/admin.automatic.search.date.js')}}"></script>
    <script>
        //信息查看
        function showInfo(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"日志查看",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["50%","60%"],
                content:url
            })
        }
    </script>
@endsection
