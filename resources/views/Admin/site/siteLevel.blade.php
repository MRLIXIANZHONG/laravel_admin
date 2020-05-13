<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\User;
$getAuth = getAuth();
?>
@extends('Admin.base.blank')
@section('title','站点域名配置')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">站点域名配置</li>
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
                                <select class="form-control search-select" name="stype" id="stype">
                                    <option value="0">请选择产品</option>
                                    <option value="1" @if(!empty($_GET['stype']) and $_GET['stype']==1)selected="selected" @endif>建材</option>
                                    <option value="2" @if(!empty($_GET['stype']) and $_GET['stype']==2)selected="selected" @endif>楼盘</option>
                                </select>
                            </div>
                            <input type="hidden" name="siteid" value="{{$_GET['siteid']}}">
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

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$re->total()!!}</span>条</h3>
                    <div class="pull-right mt10">
                        @if($getAuth->can('admin/site/site_level_add'))
                        <a href="javascript:void(0)" onClick="dataAdd('{{url('admin/site/site_level_add?siteid='.$_GET['siteid'])}}')"
                           class="btn btn-sm btn-primary">添加域名配置</a>
                        @endif
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>站点名</th>
                                <th>域名标识</th>
                                <th>顶级域名</th>
                                <th>系统</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>
                                        {{str_limit($v->site3E21->site_name,30)}}
                                    </td>
                                    <td>{{str_limit($v->field_label,30)}}</td>
                                    <td>{{str_limit($v->top_label,50)}}</td>
                                    <td>{{site_level_type()[$v->type]}}</td>
                                    @if($v->status ==1)
                                        <td style="color:deepskyblue">正常</td>
                                        @else
                                        <td style="color:red">未开通</td>
                                     @endif

                                    <td>
                                        @if($getAuth->can('admin/site/site_level_edit'))
                                            <button type="button" class="btn btn-info" title="修改" onClick="dataEdit('{{url('admin/site/site_level_edit?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                        @endif
                                        @if($getAuth->can('admin/site/site_level_del'))
                                            <button type="button" class="btn btn-danger" title="删除配置"
                                                    onClick="dataDelete({{$v->id}})"><i class="fa fa-trash"></i></button>
                                        @endif
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
        //二级域名添加
        function dataAdd(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"站点域名",
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
        function dataDelete(id) {
            layer.confirm('确定删除该域名?',{btn:['确定','取消']},function () {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    type : 'get',
                    url: "{{url('admin/site/site_level_del')}}",
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
    </script>
@endsection
