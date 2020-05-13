<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$getAuth = getAuth();
?>
@extends('Admin.base.blank')
@section('title','公司管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">公司管理</li>
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
                            <input type="text" class="form-control search-btn" placeholder="搜索公司名" name="name" @if(!empty($_GET['name'])) value="{{$_GET['name']}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索联系人" name="liaison"@if(!empty($_GET['liaison'])) value="{{$_GET['liaison'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" class="form-control search-btn" placeholder="搜索联系电话"
                                   name="liaison_phone" @if(!empty($_GET['liaison_phone'])) value="{{$_GET['liaison_phone'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/company/index')}}" class="btn btn-default pull-right">
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
                        @if($getAuth->can('admin/company/create'))
                            <a href="javascript:void(0)" onClick="dataAdd('{{url('admin/company/create')}}')"
                               class="btn btn-sm btn-primary">添加公司</a>
                        @endif
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>公司ID</th>
                                <th>公司名</th>
                                <th>公司地址</th>
                                <th>联系人</th>
                                <th>联系电话</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{$v->id}}</td>
                                    <td>{{str_limit($v->name,25)}}</td>
                                    <td>{{str_limit($v->add,25)}}</td>
                                    <td>{{str_limit($v->liaison,25)}}</td>
                                    <td>{{str_limit($v->liaison_phone,25)}}</td>
                                    <td>{{str_limit($v->remark,25)}}</td>
                                    <td>
                                        @if($getAuth->can('admin/company/update'))
                                            <button type="button" class="btn btn-info" title="修改" onClick="dataEdit('{{url('admin/company/update?id='.$v->id)}}')"><i class="fa  fa-edit"></i></button>
                                        @endif
                                        @if($getAuth->can('admin/company/delete'))
                                            <button type="button" class="btn btn-danger" title="销毁公司" onClick="dataDelete({{$v->id}})"><i class="fa fa-trash"></i></button>
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
        //公司添加
        function dataAdd(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"公司添加",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["65%","65%"],
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
                area:["65%","65%"],
                content:url
            })
        }
        //公司删除
        function dataDelete(id) {
	        layer.confirm('确定删除该公司?',{btn:['确定','取消']},function () {
		        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		        $.ajax({
			        type : 'get',
			        url: "{{url('admin/company/delete')}}",
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
