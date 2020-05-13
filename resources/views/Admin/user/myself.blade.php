<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\User;
$userInfo = getAuth();
?>
@extends('Admin.base.blank')
@section('title','首页')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active"> 个人中心</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{$userInfo->avatar ? asset
                    ($userInfo->avatar) : asset('AdminLTE-2.4.18/dist/img/user2-160x160.jpg')}}" alt="User profile picture">
                    <label style="background-color:#07cdae; display:block; width: 100px; margin:10px auto 0; padding:
                     5px 0; color:#fff;text-align: center; " title="建议上传比例5:3大小3MB以内图片">重新上传头像
                        <input type="file" name="avatar" value="{{asset($userInfo->avatar)}}" id="avatar" class="btn
                        btn-primary" style="display:none; " accept="image/gif,image/jpeg,image/jpg,image/png,image/svg">
                    </label>
                    <form role="form" action="{{url('admin/myself')}}" method="post" target="nm_iframe" id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        <input type="hidden" name="id" value="{{$userInfo->id}}" >
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>用户名</label>
                                <input type="text" class="form-control" placeholder="用户名" required
                                       name="username" value="{{$userInfo->username}}">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>姓名</label>
                                <input type="text" class="form-control" placeholder="姓名" required
                                       name="name" value="{{$userInfo->name}}">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>邮箱</label>
                                <input type="email" class="form-control" placeholder="邮箱" required
                                       name="email" value="{{$userInfo->email}}">
                            </div>
                            <div class="form-group">
                                <label>密码</label>
                                <input type="text" class="form-control" placeholder="密码,不修改密码请勿填写,密码修改成功后将重新登陆" name="pwd">
                            </div>
                            <div class="form-group">
                                <label>确认密码</label>
                                <input type="text" class="form-control" placeholder="确认密码" name="repwd">
                            </div>
                            <div class="form-group">
                                <label><i class="fa fa-close"></i>状态</label>
                                <input type="text" class="form-control" placeholder="状态" disabled
                                       value="{{$userInfo->type_text}}">
                            </div>
                            @php $userRole = isset($userInfo->getRoleNames()[0]) ? $userInfo->getRoleNames()[0] : '未绑定' @endphp
                            <div class="form-group">
                                <label><i class="fa fa-close"></i>角色</label>
                                <input type="text" class="form-control" placeholder="状态" disabled
                                       value="{{$userRole}}">
                            </div>

                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="id_input_text" name="nm_input_text" />
                            <button type="submit" class="btn btn-primary" id="id_submit" name="nm_submit">提交修改</button>
                        </div>
                    </form>
                    <iframe id="id_iframe" name="nm_iframe" style="display:none;"></iframe>
                </div>
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
    //头像修改
    $("#avatar").change(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var filesrc = document.getElementById("avatar").files[0];
        $(".avatar").attr("src",URL.createObjectURL(filesrc));
        var formObj = new FormData();
        formObj.append("avatar",filesrc);
        var index = layer.load(1, {
            shade: [0.1, '#fff']
        });
        $.ajax({
            url:"{{url('admin/edit_avatar')}}",
            type:"post",
            data:formObj,
            cache:false,
            dataType:"JSON",
            contentType: false,
            processData: false,
            success:function(datas){
                layer.close(index);
                if(datas['code']===200){
                    layer.msg(datas['infor'], {time:2000},function () {
                        parent.window.location.reload();
                    })
                }else{
                    layer.msg(datas['infor'],{time:2000})
                }
            },error:function(datas){
                $("#code-mark").html('网络错误');
            }
        })
    });
    </script>
@endsection
