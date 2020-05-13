<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\Permission;
?>
@extends('Admin.base.popup')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{{$allGroups->count()}}</span>条</h3>
                    @if(getAuth()->can('admin/permission/group/create'))
                    <div class="pull-right mt10">
                        <a href="javascript:void(0)" onClick="groupAdd('{{url('admin/permission/group/create')}}')"
                           class="btn btn-sm btn-primary">添加权限组</a>
                    </div>
                    @endif
                </div>
                @if($allGroups->count())
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>id</th>
                            <th>组名</th>
                            <th>创建时间</th>
                            <th>上次更新</th>
                            <th>包含权限</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="data-list">
                        @foreach($allGroups as $k=>$v)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$v->id}}</td>
                                <td>{{$v->name}}</td>
                                <td>{{$v->created_at}}</td>
                                <td>{{$v->updated_at}}</td>
                                <td>{{count((new Permission)->getGroupPermission($v->id))}}</td>
                                <td>
                                    @if(getAuth()->can('admin/permission/group/update'))
                                    <button type="button" class="btn btn-info" title="修改" onClick="edit_group('{{url('admin/permission/group/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                    @endif
                                    @if(getAuth()->can('admin/permission/group/delete'))
                                    <button type="button" class="btn btn-danger" title="销毁组" onClick="deleteGroup({{$v->id}})"><i class="fa fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
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
    <script>
        $(function(){
            var textStr;
            $("#id_iframe").on('load',function () {
                textStr = $(this);
                var re = textStr[0].contentDocument.body.textContent;
                if(re === "" || re == null){
                    console.log("后台初始化完成,等待数据接入");
                }else{
                    var datas = JSON.parse(re);
                    if(datas['code']===200){
                        layer.msg(datas['infor'], {time:2000},function () {
                            parent.window.location.reload();
                        })
                    }else{
                        layer.msg(datas['infor'],{time:2000})
                    }
                }
            })
        });
        //弹窗1
        function edit_group(url){
	        layer.open({
		        type:2,           //类型，解析url
		        closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
		        title:"权限组修改",
		        shadeClose: false, //点击遮罩区域是否关闭页面
		        shade: 0.6,       //遮罩透明度
		        area:["50%","45%"],
		        content:url
	        })
        }
        //弹窗1
        function groupAdd(url){
	        layer.open({
		        type:2,           //类型，解析url
		        closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
		        title:"权限组添加",
		        shadeClose: false, //点击遮罩区域是否关闭页面
		        shade: 0.6,       //遮罩透明度
		        area:["50%","45%"],
		        content:url
	        })
        }
        //权限删除
        function deleteGroup(id) {
	        layer.confirm('确定删除该权限组?',{btn:['确定','取消']},function () {
		        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		        $.ajax({
			        type : 'get',
			        url: "{{url('admin/permission/group/delete')}}",
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


