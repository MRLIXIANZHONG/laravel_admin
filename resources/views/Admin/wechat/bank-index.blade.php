<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\User;
$getAuth = getAuth();
?>
@extends('Admin.base.blank')
@section('title','支付配置')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">支付配置</li>
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
                            <input type="text" class="form-control search-btn" placeholder="搜索公司名" name="compname"
                                   @if(!empty($_GET['compname'])) value="{{$_GET['compname']}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/wechat/bank/index')}}" class="btn btn-default pull-right">
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
                        @if($getAuth->can('admin/wechat/bank/create'))
                            <a href="javascript:void(0)" onClick="dataAdd('{{url('admin/wechat/bank/create')}}')"
                               class="btn btn-sm btn-primary">添加支付配置到站点</a>
                        @endif
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>公司名</th>
                                <th>站点</th>
                                <th>支付宝PC扫码</th>
                                <th>支付宝手机网页</th>
                                <th>支付宝APP</th>
                                <th>微信支付</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{str_limit($v->compname,25)}}</td>
                                    <td>{{str_limit(@$v->site3E21->site_name,25)}}</td>
                                    <td>{{str_limit($v->isali_text,25)}}</td>
                                    <td>{{str_limit($v->isaliphone_text,25)}}</td>
                                    <td>{{str_limit($v->IsAlipayApp_text,25)}}</td>
                                    <td>{{str_limit($v->isWx_text,25)}}</td>
                                    <td>
                                        @if($getAuth->can('admin/wechat/bank/update'))
                                            <button type="button" class="btn btn-info" title="修改" onClick="dataEdit('{{url('admin/wechat/bank/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7">
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
        //站点支付配置添加
        function dataAdd(url) {
	        layer.open({
		        type:2,           //类型，解析url
		        closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
		        title:"站点支付配置添加",
		        shadeClose: false, //点击遮罩区域是否关闭页面
		        shade: 0.6,       //遮罩透明度
		        area:["80%","80%"],
		        content:url
	        })
        }
    </script>
@endsection
