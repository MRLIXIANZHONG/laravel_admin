<?php

use App\Models\User;
?>
@extends('Admin.base.blank')
@section('title','余额管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">余额管理</li>
@endsection
@section('content')
    @if(getAuth()->siteid == \App\Models\User::SUPERADMIN)
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                </div>
                <div class="box-body">
                    <form method="get" class="forms-sample"  target="search_iframe">

                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="siteid" id="siteid">
                                <option value="0">请选择站点</option>
                                @foreach($site as $v1)
                                    <option value="{{$v1->siteid}}" @if(!empty($_GET['siteid']) and $_GET['siteid']==$v1->siteid)selected="selected" @endif>{{$v1->site_name}}</option>
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
                        <a href="{{url('admin/capital/index')}}" class="btn btn-default pull-right">
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

                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>站点名称</th>
                                <th>账户余额</th>

                                @if(getAuth()->siteid != \App\Models\User::SUPERADMIN)
                                <th>操作</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{str_limit($v->site_name,25)}}</td>
                                    <td>{{$v->fund}}</td>


                                    @if(getAuth()->siteid != \App\Models\User::SUPERADMIN)
                                    <td>

                                           {{-- <button type="button" class="btn btn-primary" title="修改" onClick="dataEdit('{{url('admin/sms/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>--}}
                                           {{-- <button type="button" class="btn btn-info" title="充值" onClick="dataEdit('{{url('admin/sms/recharge?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger" title="删除" onClick="del('{{url('admin/sms/delete?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>--}}

                                           {{-- <button  @if(getAuth()->siteid == User::SUPERADMIN) disabled @endif type="button" class="btn btn-success" title="提现" onClick="carray('{{url('admin/capital/carry?id='.$v->id)}}')" ><i class="fa fa-edit"></i></button>--}}
                                            <button  type="button" class="btn btn-success" title="提现" onClick="carray('{{url('admin/capital/carry?id='.$v->id)}}')" ><i class="fa fa-edit"></i></button>

                                    </td>
                                    @endif
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
        //弹窗1
        function carray(url){
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"余额提现",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["50%","60%"],
                content:url
            })
        }

    </script>
@endsection
