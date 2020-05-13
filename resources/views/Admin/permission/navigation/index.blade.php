<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$NavigationModel = new \App\Models\Navigation;
?>
@extends('Admin.base.blank')
@section('title','导航设置')
@section('breadcrumb')
    <li><a href="{{url('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">导航设置</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 导航列表/检索导航:<span id="data-num">{{$lists->count
                    ()}}</span>条</h3>
                    <div class="pull-right mt10">
                        @if(getAuth()->can('admin/permission/navigation/create'))
                        <a href="javascript:void(0)" onClick="navigationAdd('{{url('admin/permission/navigation/create')
                        }}')"
                           class="btn btn-sm btn-primary">添加导航[导航]</a>
                        @endif
                    </div>
                </div>
                @if($lists->count())
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>链接</th>
                                <th>上级权限</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="data-list">
                            @foreach($lists as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$v->name}}</td>
                                    <td>{{$v->permission_id ? $v->permissionName : '无'}}</td>
                                    <td>
                                        {{$v->parent_id ? @$NavigationModel->getNavigationById($v->parent_id)->name :"顶级导航"}}
                                    </td>
                                    <td>{{$v->sequence}}</td>
                                    <td>
                                        @if(getAuth()->can('admin/permission/navigation/update'))
                                            <button type="button" class="btn btn-info" title="修改" onClick="edit_permission('{{url('admin/permission/navigation/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                        @endif
                                        @if(getAuth()->can('admin/permission/navigation/delete'))
                                            <button type="button" class="btn btn-danger" title="删除"
                                                    onClick="deleteNavigation({{$v->id}})"><i class="fa fa-trash"></i></button>
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
                    </div>
                @else
                    <div class="alert alert-info fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>404!</strong> 当前列表暂无数据.
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('lib/admin/admin.automatic.search.js')}}"></script>
    <script>
			//弹窗1
			function navigationAdd(url){
				layer.open({
					type:2,           //类型，解析url
					closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
					title:"导航添加",
					shadeClose: false, //点击遮罩区域是否关闭页面
					shade: 0.6,       //遮罩透明度
					area:["50%","65%"],
					content:url
				})
			}
			//导航信息修改
			function edit_permission(url) {
				layer.open({
					type:2,           //类型，解析url
					closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
					title:"导航修改",
					shadeClose: false, //点击遮罩区域是否关闭页面
					shade: 0.6,       //遮罩透明度
					area:["50%","65%"],
					content:url
				})
			}
			//导航删除
			function deleteNavigation(id) {
				layer.confirm('确定删除该导航?',{btn:['确定','取消']},function () {
					$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
					$.ajax({
						type : 'get',
						url: "{{url('admin/permission/navigation/delete')}}",
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

