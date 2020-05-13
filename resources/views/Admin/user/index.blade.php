<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\User;
?>
@extends('Admin.base.blank')
@section('title','用户管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">用户管理</li>
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
                            <input type="text" class="form-control search-btn" placeholder="搜索用户名" name="username"
                                   @if(!empty($_GET['username'])) value="{{$_GET['username']}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索姓名" name="name"
                                   @if(!empty($_GET['name'])) value="{{$_GET['name'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="text" class="form-control search-btn" placeholder="搜索邮箱" name="email"
                                   @if(!empty($_GET['email'])) value="{{$_GET['email'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                            <option value="10">显示条数(10)</option>
                            @foreach(getSizeArr() as $v)
                                <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                            @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/user/index')}}" class="btn btn-default pull-right">
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
                    @if(getAuth()->can('admin/user/create'))
                    <a href="javascript:void(0)" onClick="userAdd('{{url('admin/user/create')}}')"
                       class="btn btn-sm btn-primary">添加用户</a>
                    @endif
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>用户名</th>
                            <th>姓名</th>
                            <th>邮箱</th>
                            <th>状态</th>
                            <th>盟友</th>
                            <th>角色</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($re as $k=>$v)
                        @php
                            $userRole = isset($v->getRoleNames()[0]) ? $v->getRoleNames()[0] : '未绑定';
                            $dis = $v->id == User::SUPERADMIN ? 'disabled' : '';
                        @endphp
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$v->username}}</td>
                            <td>{{$v->name}}</td>
                            <td>{{$v->email}}</td>
                            <td>
                                @if(getAuth()->can('admin/user/type/update'))
                                <button {{$dis}} type="button" class="z-switch switch-anim  btn-editstatus @if($v->type == User::TYPEACTIVITY)checked @endif" title="点击即可修改用户状态" value="{{$v->id}}"></button>
                                @else
                                {{$v->type_text}}
                                @endif
                            </td>
                            <td>
                                @if(getAuth()->can('admin/site/index'))
                                    <a href="{{url("admin/site/index?siteid={$v->siteid}")}}" title="点击跳转查看该用户管理的站点">{{@$v->site3E21->site_name}}</a>
                                @else
                                    {{@$v->site3E21->site_name}}
                                @endif
                            </td>
                            <td>{{$userRole}}</td>
                            <td>
                                @php $dis = $v->id == User::SUPERADMIN ? 'disabled' : ''; @endphp
                                @if(getAuth()->can('admin/user/update'))
                                <button {{$dis}} type="button" class="btn btn-info" title="修改" onClick="edit_userInfo('{{url('admin/user/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                @endif
                                @if(getAuth()->can('admin/user/reset_psword'))
                                    <button {{$dis}} type="button" class="btn btn-warning" title="重置管理员密码" onClick="reset_psword('{{$v->id}}','{{$v->username}}')"><i class="fa fa-refresh"></i></button>
                                @endif
                                @if(getAuth()->can('admin/user/delete'))
                                <button {{$dis}} type="button" class="btn btn-danger" title="销毁用户" onClick="deleteUser({{$v->id}})"><i class="fa fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="8">
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
	    // 修改用户状态
	    $(document).on("click",".btn-editstatus",function(){
		    var _that = $(this);
		    var text = $(this).hasClass("checked") ? "是否确认修改为禁用状态？" : "是否确认修改为正常状态？";
		    var id = $(this).attr('value');
		    layui.use("layer",function(){
			    var layer = layui.layer;
			    layer.confirm( text ,{
				    btn: ["确定","取消"]
			    },function(){
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
				    $.ajax({
					    url:"{{url('admin/user/type/update')}}",
					    type:"GET",
                        method:'put',
                        data:{id:id},
					    dataType: "json",
					    success:function(datas){
                            if(datas['code']===200){
							    if(_that.hasClass("checked")){
								    _that.removeClass("checked");
								    layer.closeAll();
							    }else{
								    _that.addClass("checked");
								    layer.closeAll();
							    }
						    }else{
							    layer.msg(datas['infor'],function () {return false;})
						    }
					    },
					    error:function(er){
					    }
				    })
			    })
		    })
	    });
        //管理员密码重置
        function reset_psword(id,username) {
            layer.open({
                type: 1,
                title:'重置管理员[管理员名:'+username+']的密码',
                skin:'layui-layer-rim',
                area:['450px', 'auto'],

                content: ' <div class="row" style="width: 420px;  margin-left:7px; margin-top:10px;">'
                        +'<div class="col-sm-12">'
                        +'<div class="input-group">'
                        +'<span class="input-group-addon"> 新  密  码   :</span>'
                        +'<input id="firstpwd" type="password" class="form-control" placeholder="请输入密码">'
                        +'</div>'
                        +'</div>'
                        +'<div class="col-sm-12" style="margin-top: 10px">'
                        +'<div class="input-group">'
                        +'<span class="input-group-addon">确认密码:</span>'
                        +'<input id="secondpwd" type="password" class="form-control" placeholder="请再输入一次密码">'
                        +'</div>'
                        +'</div>'
                        +'</div>'
                ,
                btn:['确定','取消'],
                btn1: function (index,layero) {
                    var firstpwd = $("#firstpwd").val();
                    var secondpwd = $("#secondpwd").val();
                    if(firstpwd ==="" || secondpwd===""){
                        return layer.msg("请完整输入新密码及确认密码",{time:2000});
                    }
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                    $.ajax({
                        type : 'get',
                        url: "{{url('admin/user/reset_psword')}}",
                        data:{id:id,firstpwd:firstpwd,secondpwd:secondpwd},
                        method:'put',
                        dataType:"json",
                        success:function (datas) {
                            if(datas['code']===200){
                                layer.msg(datas['infor'], {time:2000},function () {
                                    layer.close(index);
                                })
                            }else{
                                layer.msg(datas['infor'],{time:4000})
                            }
                        }
                    });
                },
                btn2:function (index,layero) {
                    layer.close(index);
                }

            });
        }
	    //弹窗1
	    function userAdd(url){
		    layer.open({
			    type:2,           //类型，解析url
			    closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
			    title:"用户添加",
			    shadeClose: false, //点击遮罩区域是否关闭页面
			    shade: 0.6,       //遮罩透明度
			    area:["50%","60%"],
			    content:url
		    })
	    }
	    //用户信息修改
        function edit_userInfo(url) {
	        layer.open({
		        type:2,           //类型，解析url
		        closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
		        title:"用户修改",
		        shadeClose: false, //点击遮罩区域是否关闭页面
		        shade: 0.6,       //遮罩透明度
		        area:["50%","60%"],
		        content:url
	        })
        }
        //用户删除
        function deleteUser(id) {
            layer.confirm('确定删除该用户?',{btn:['确定','取消']},function () {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                  type : 'get',
                  url: "{{url('admin/user/delete')}}",
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
