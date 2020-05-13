<?php

use App\Models\User;
?>
@extends('Admin.base.blank')
@section('title','短信管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">短信管理</li>
@endsection
@section('content')
    @if(getAuth()->siteid == User::SUPERADMIN)
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                </div>
                <div class="box-body">
                    <form method="get" class="forms-sample"  target="search_iframe">
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索用户名" name="user_name"
                                   @if(!empty($_GET['user_name'])) value="{{$_GET['user_name']}}" @endif>
                        </div>

                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/sms/index')}}" class="btn btn-default pull-right">
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
    @endif
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$re->total()!!}</span>条</h3>
                    <div class="pull-right mt10">
                        @if(getAuth()->can('admin/sms/create'))
                            {{--<a href="javascript:void(0)" onClick="smsAdd('{{url('admin/sms/create')}}')"
                               class="btn btn-sm btn-primary">添加商家短信</a>--}}
                        @endif
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>站点</th>
                                <th>剩余短信条数</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{@$v->site3E21->site_name}}</td>
{{--                                    <td>{{str_limit($v->user_name,25)}}</td>--}}
                                    <td>{{$v->msg_count}}</td>
                                    @if($v->status ==1)
                                    <td>正常</td>
                                    @else
                                        <td>短信已使用完</td>
                                    @endif
                                    <td>
                                        @if(getAuth()->siteid == User::SUPERADMIN)
                                           {{-- <button type="button" class="btn btn-primary" title="修改" onClick="dataEdit('{{url('admin/sms/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>--}}

                                            <button type="button" class="btn btn-danger" title="删除" onClick="del('{{url('admin/sms/delete?id='.$v->id)}}')"><i class="fa fa-trash"></i></button>
                                            @else
                                            <button type="button" class="btn btn-info" title="充值" onClick="dataEdit('{{url('admin/sms/recharge?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">
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
        //信息修改
        function dataEdit(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"短信充值",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["30%","50%"],
                content:url
            })
        }
        //弹窗1
        function smsAdd(url){
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"短信添加",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["50%","60%"],
                content:url
            })
        }

        function del(url) {
            $.get(url,{_token:"{{csrf_token()}}"},function (data) {
                if(data['code']===200){
                    layer.msg(data['infor'], {time:2000},function () {
                        parent.window.location.reload();
                    })
                }else{
                    layer.msg(data['infor'],{time:2000})
                }
            })
        }

    </script>
@endsection
