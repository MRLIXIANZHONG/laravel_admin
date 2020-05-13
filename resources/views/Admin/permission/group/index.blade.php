<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\Permission;
$id = isset($_GET['id']) ? $_GET['id'] : Permission::BASUCSGROUP;
?>
@extends('Admin.base.blank')
@section('title','权限组管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">权限组管理</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            @if(getAuth()->can('admin/permission/group/list'))
            <a href="javascript:void(0)" onClick="groupList('{{url('admin/permission/group/list')}}')" class="btn btn-primary btn-block margin-bottom">点此进行组管理</a>
            @endif
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">权限组列表</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        @foreach($allGroups as $k=>$v)
                            <li @if(($id == $v->id)) class="active" @endif>
                                <a href="{{url('admin/permission/group/index?id='.$v->id)}}"><i class="fa fa-list-ul"></i>
                                    {{$v->name}}
                                    <span class="label label-primary pull-right">
                                        {{(new Permission)->getGroupPermission($v->id)->count()}}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">其他</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li @if($id == Permission::BASUCSGROUP) class="active" @endif>
                            <a href="{{url('admin/permission/group/index?id='.Permission::BASUCSGROUP)}}">
                                <i class="fa fa-circle-o text-red"></i>基础权限组
                                <span class="label label-primary pull-right">
                                        {{(new Permission)->getGroupPermission(Permission::BASUCSGROUP)->count()}}
                                    </span>
                            </a>
                        </li>
                        <li @if($id == Permission::PGNO) class="active" @endif>
                            <a href="{{url('admin/permission/group/index?id='.Permission::PGNO)}}">
                                <i class="fa fa-circle-o text-black"></i>未定义组权限
                                <span class="label label-primary pull-right">
                                        {{(new Permission)->getGroupPermission(Permission::PGNO)->count()}}
                                    </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">[{{$groupName}}]组内权限</h3>
                    @if(getAuth()->can('admin/permission/group/movein'))
                    @if((int)$id !== Permission::PGNO)
                    <div class="alert alert-dismissable fade in">
                        <strong>说明:</strong> 权限组内权限释放(删除权限组、移出权限组内某个权限...)后,角色对于具体权限的所有权也将释放.
                    </div>
                    <div class="box-tools pull-right">
                        <a href="javascript:void(0)" onClick="groupMovein('{{url('admin/permission/group/movein?groupId='.$id)}}')" class="btn btn-sm btn-primary">拉取未定义组的权限到组内</a>
                    </div>
                    @endif
                    @endif
{{--                    <div class="box-tools pull-right">--}}
{{--                        <div class="has-feedback">--}}
{{--                            <input type="text" class="form-control input-sm" placeholder="搜索权限名称或地址">--}}
{{--                            <span class="glyphicon glyphicon-search form-control-feedback"></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                @if($groupRoute->count())
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>
                                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                    </button>
                                </th>
                                <th></th>
                                <th>路由地址</th>
                                <th>名称/描述</th>
                                <th></th>
                                <th>上次更新</th>
                            </tr>
                            @foreach($groupRoute as $k=>$v)
                            <tr>
                                <td><input type="checkbox" name="ckbox" value="{{$v->id}}"></td>
                                <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                <td class="mailbox-name"><a href="javascript:void(0)">{{$v->name}}</a></td>
                                <td class="mailbox-display_name"><b>{{$v->display_name ?: '暂未定义'}}</b>-{{$v->description ?: '暂无描述'}}
                                </td>
                                <td class="mailbox-attachment"></td>
                                <td class="mailbox-updated_at">{{$v->updated_at}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                        </button>
                        @if(getAuth()->can('admin/permission/group/moveout'))
                        @if((int)$id !== Permission::PGNO)
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm btn-delete" title="批量移除权限组"><i class="fa fa-trash-o"></i></button>
                        </div>
                        @endif
                        @endif
                        <!-- /.btn-group -->
                        <button type="button" class="btn btn-default btn-sm" onClick="location.reload();"><i class="fa
                        fa-refresh"></i></button>
                        <div class="pull-right">
                            共[{{$groupRoute->count()}}]条
                            <!-- /.btn-group -->
                        </div>
                        <!-- /.pull-right -->
                    </div>
                </div>
                @else
                    <div class="alert alert-info fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>404!</strong> 当前列表暂无数据.
                    </div>
                @endif
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
@endsection
@section('script')
    <!-- js引入/多选/特效 -->
    <script src="{{asset('lib/admin/admin.group.index.js')}}"></script>
    <script>
	    //弹窗1
	    function groupList(url){
		    layer.open({
			    type:2,           //类型，解析url
			    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
			    title:"所有权限组",
			    shadeClose: false, //点击遮罩区域是否关闭页面
			    shade: 0.6,       //遮罩透明度
			    area:["65%","80%"],
			    content:url
		    })
	    }
        //弹窗2
        function groupMovein(url){
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"拉取权限到组内",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["65%","80%"],
                content:url
            })
        }
        //批量移出权限
        $(".btn-delete").click(function(){
	        var idArr= new Array();
            $("input[name='ckbox']:checked").each(function() {
                idArr.push($(this).val());
            });
	        if(!idArr[0]){
                alert("请选择要移出组内的权限！");
            }else{
                idArr = idArr.join(",");
                if(confirm("是否确定批量移出选中的权限？")){
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                    $.ajax({
                        type : "POST",
                        url : "{{url('admin/permission/group/moveout')}}",
                        data: {idArr:idArr},
                        method:'put',
                        dataType : "json",
                        success:function(datas){
                            if(datas['code']===200) {
                                layer.msg(datas['infor'], {time: 1000},function(){
                                    parent.window.location.reload();
                                })
                            }else{
                                layer.msg(datas['infor'],{time:3000},function () {
                                    parent.window.location.reload();
                                })
                            }
                        }//ALAX调用成功
                        ,error:function () {
                            alert("网络异常！");
                        }
                    });
                }
            }
        });
    </script>
@endsection

