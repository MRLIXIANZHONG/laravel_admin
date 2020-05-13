<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.popup')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 角色列表/共有角色:<span
                            id="data-num">{{$lists->count()}}</span>条</h3>
                    <div class="pull-right mt10">
                        @if(getAuth()->can('admin/permission/role/create'))
                        <a href="javascript:void(0)" onClick="roleAdd('{{url('admin/permission/role/create')}}')" class="btn btn-sm btn-primary">添加角色</a>
                        @endif
                    </div>
                </div>
                @if($lists->count())
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>guard</th>
                                <th>名称</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>描述</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="data-list">
                            @foreach($lists as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$v->guard_name}}</td>
                                    <td>{{$v->name}}</td>
                                    <td>{{$v->created_at}}</td>
                                    <td>{{$v->updated_at}}</td>
                                    <td>{{$v->description}}</td>
                                    <td>
                                        @if(getAuth()->can('admin/permission/role/update'))
                                        <button type="button" class="btn btn-info" title="修改" onClick="edit('{{url('admin/permission/role/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                        @endif
                                        @if(getAuth()->can('admin/permission/role/delete'))
                                        <button type="button" class="btn btn-danger" title="删除" onClick="deleteRole({{$v->id}})"><i class="fa fa-trash"></i></button>
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
	    //弹窗1
	    function roleAdd(url){
		    layer.open({
			    type:2,           //类型，解析url
			    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
			    title:"角色添加",
			    shadeClose: false, //点击遮罩区域是否关闭页面
			    shade: 0.6,       //遮罩透明度
			    area:["50%","55%"],
			    content:url
		    })
	    }
	    //角色信息修改
	    function edit(url) {
		    layer.open({
			    type:2,           //类型，解析url
			    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
			    title:"角色修改",
			    shadeClose: false, //点击遮罩区域是否关闭页面
			    shade: 0.6,       //遮罩透明度
			    area:["50%","55%"],
			    content:url
		    })
	    }
	    //角色删除
	    function deleteRole(id) {
		    layer.confirm('确定删除该角色?',{btn:['确定','取消']},function () {
			    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
			    $.ajax({
				    type : 'get',
				    url: "{{url('admin/permission/role/delete')}}",
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



