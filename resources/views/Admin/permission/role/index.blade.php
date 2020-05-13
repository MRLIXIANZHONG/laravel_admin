<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$PermissionModel = new \App\Models\Permission;
$id = isset($_GET['id']) ? $_GET['id'] : null;
?>
<style>
    .panel-primary{border-color: #337ab7!important; }
    .panel-primary>.panel-heading{background-color: #3c8dbc!important; }
    .panel .panel-body{padding: 10px 8px; }
    .panel .panel-body .w-class{margin: 5px 0; padding: 0 5px; text-overflow: ellipsis; word-break: break-all; overflow: hidden; white-space: nowrap; }
    .panel .panel-body .w-class input{display: none; }
    .panel .panel-body .w-class .glyphicon{background-color: #aaa; color: #fff; display: block; width: 20px; height: 20px; float: left; margin-right: 5px; line-height: 20px; text-align: center; border-radius: 3px; box-sizing: border-box; }
    .panel .panel-body .w-class .icon{background-color: #aaa; width: 16px; height: 16px; box-sizing: border-box; border-radius: 50%; border: solid 2px #fff; box-shadow: 0 0 0 2px #aaa; float: left; margin: 2px 5px 0 0; transition: all 0.4s; }
    .panel .panel-body .w-class input:checked+.icon{background-color: #3c8dbc; box-shadow: 0 0 0 2px #3c8dbc; }
</style>
@extends('Admin.base.blank')
@section('title','角色管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">角色管理</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            @if(getAuth()->can('admin/permission/role/list'))
                <a href="javascript:void(0)" onClick="roleList('{{url('admin/permission/role/list')}}')"
                   class="btn btn-primary btn-block margin-bottom">点此进行角色管理</a>
            @endif
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">角色列表</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        @foreach($lists as $k=>$v)
                            <li @if(($id == $v->id)) class="active" @endif>
                                <a href="{{url('admin/permission/role/index?id='.$v->id)}}"><i class="fa fa-list-ul"></i>
                                    {{$v->name}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @if(getAuth()->can('admin/permission/role/initialize'))
                {{--            <a href="javascript:void(0)" onClick="initialize_SuperAdmin()"--}}
                {{--               class="btn btn-info btn-block margin-bottom" disabled>超管权限初始化</a>--}}
                <a href="javascript:void(0)" class="btn btn-info btn-block margin-bottom" disabled>超管权限初始化</a>
            @endif
        </div>
        <div class="col-md-9">
            <div class="">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$role ? "[$role->name]权限" : '请先选择角色'}}</h3>
                    <div class="box-tools pull-right">
                        已勾选权限[{{$roleRoute ? $roleRoute->count() : 0}}个]
                    </div>
                </div>
                <div class="box-body no-padding">
                    <!-- <div class="table-responsive mailbox-messages"> -->
                    @if($roleRoute)
                        @php $allGroups = $PermissionModel->GroupGetAll(); @endphp
                        <div class="callout callout-info">
                            <h4>角色权限绑定说明</h4>
                            勾选的权限,即用户拥有的权限.需要修改权限?    <b style="color: #0C0C0C;">确认勾选</b>完毕后点击保存按钮即可
                        </div>
                        <div class="col-xs-12">


                            @foreach($allGroups as $v)
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{$v->name}}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <label class="w-class">
                                            <input type="checkbox" class="check-all" style="dislpay: none;" name="all" value="233"><span class="icon"></span>全选
                                        </label>
                                        @php $groupRoute = $PermissionModel->getGroupPermission($v->id); @endphp
                                        @foreach($groupRoute as $Permission)
                                            <label class="w-class">
                                                <input type="checkbox" class="form-check-input w-check" style="dislpay: none;" name="permissions"
                                                       value="{{$Permission->name}}" @if($role->hasPermissionTo ($Permission->name)) checked @endif>
                                                <span class="icon"></span>
                                                {{$Permission->display_name}}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">基本权限组(每个账号都会拥有该组所有权限)</h3>
                                </div>
                                <div class="panel-body">
                                    @php
                                        $BasucsgroupRoute = $PermissionModel->getGroupPermission($PermissionModel::BASUCSGROUP);
                                    @endphp
                                    @foreach($BasucsgroupRoute as $Permission)
                                        <label class="w-class">
                                            <input type="checkbox" class="form-check-input" style="dislpay: none;" checked>{{$Permission->display_name}}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @if(getAuth()->can('admin/permission/role/binding'))
                                <div style="padding-bottom: 15px">
                                    <button type="button" class="btn btn-primary btn-block" onClick="submitRolePermission()">确认提交权限勾选</button>
                                </div>
                        @endif


                        <!-- <div class="box box-default">
                            @foreach($allGroups as $v)
                            <div class="box-header with-border">
                                <h3 class="box-title">{{$v->name}}</h3>
                                </div>
                                <div class="box-body">
                                    @php $groupRoute = $PermissionModel->getGroupPermission($v->id); @endphp
                            @foreach($groupRoute as $Permission)
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" class="form-check-input" name="permissions"
                                               value="{{$Permission->name}}" @if($role->hasPermissionTo
                                               ($Permission->name)) checked @endif>
                                        {{$Permission->display_name}}
                            @endforeach
                                </div>
                            @endforeach
                            <div class="box-header with-border">
                                <h3 class="box-title">基本权限组(每个账号都会拥有该组所有权限,不勾也没用)</h3>
                            </div>
                            <div class="box-body">
@php
                            $BasucsgroupRoute = $PermissionModel->getGroupPermission($PermissionModel::BASUCSGROUP);
                        @endphp
                        @foreach($BasucsgroupRoute as $Permission)
                            <label>
                                <input type="checkbox" class="form-check-input" checked>{{$Permission->display_name}}
                                </label>
@endforeach
                            </div>
@if(getAuth()->can('admin/permission/role/binding'))
                            <div class="box-body">
                                <button type="button" class="btn btn-primary btn-block" onClick="submitRolePermission()">确认提交权限勾选</button>
                            </div>
@endif
                            </div> -->
                        </div>
                    @else
                        <div class="alert alert-info fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>👈点击左侧角色列表后即可编辑指定角色权限</strong>
                        </div>
                @endif
                <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- js引入/多选/特效 -->
    <script src="{{asset('lib/admin/admin.group.index.js')}}"></script>
    <script>
        $(function(){
            $.each($(".panel-body"),function(i,v){
                var checkboxs = $(this).find(".w-check");
                var checkAll = $(this).find(".check-all");
                var all = 0;
                $.each(checkboxs,function(index,item){
                    if(!$(this).is(":checked")){
                        all++;
                    }
                });
                if(!all){
                    checkAll.prop("checked","checked");
                }
            })
            $(".check-all").on("click",function(){
                if($(this).is(":checked")){
                    $.each($(this).parents(".panel-body").find(".w-check"),function(index,item){
                        this.checked=true
                    });
                }else{
                    $.each($(this).parents(".panel-body").find(".w-check"),function(index,item){
                        this.checked=false;
                    });
                }
            });
            $(".w-check").on("click",function(){
                if($(this).is(":checked")){
                    var _this = $(this);
                    var obj =  $(this).parents(".panel-body").find(".w-check");
                    var wi = 0;
                    $.each(obj,function(index,item){
                        if($(this).is(":checked")){
                            wi++;
                        }else{
                            // _this.parents(".panel-body").find(".check-all")[0].checked = false;
                            _this.parents(".panel-body").find(".check-all").prop("checked",false);
                            return false;
                        }
                    });
                    if(wi==obj.length){
                        _this.parents(".panel-body").find(".check-all").prop('checked',true);
                    }
                }else{
                    $(this).parents(".panel-body").find(".check-all").prop("checked",false);
                }
            });
        });

        $(".iCheck-helper").on("click",function(){
            console.log(4648)
        })
        //弹窗1
        function roleList(url){
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"所有角色",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["65%","80%"],
                content:url
            })
        }
        //提交权限绑定
        function submitRolePermission() {
            var permmissionArr= new Array();
            $("input[name='permissions']:checked").each(function() {
                permmissionArr.push($(this).val());
            });
            if(!permmissionArr[0]){
                alert("除基本权限外,不可全部为空！");
            }else{
                if(confirm("是否确定提交角色权限绑定？")){
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                    $.ajax({
                        type : "POST",
                        url : "{{url('admin/permission/role/binding?id=').$id}}",
                        data: {permmissionArr:permmissionArr},
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
        }
        //超管角色初始化
        function initialize_SuperAdmin() {
            if(confirm("是否初始化超管权限[初始化超管权限为:拥有系统所有权限]？")){
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    type : "GET",
                    url : "{{url('admin/permission/role/initialize')}}",
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
                    }
                    ,error:function () {
                        alert("网络异常！");
                    }
                });
            }
        }
    </script>
@endsection


