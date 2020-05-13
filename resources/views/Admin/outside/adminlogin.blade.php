<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>登录{{env('APP_NAME')}}后台管理端</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/plugins/iCheck/square/blue.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{url('/admin')}}"><b>{{env('APP_NAME')}}</b>管理端</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">登录即可开始你的管理之行</p>

        <form>
            <div class="form-group has-feedback">
                <input name="user" type="text" class="form-control" placeholder="邮箱/用户名" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="密码" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" checked> 记住我
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="button" id="login" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
{{--        <a href="{{url('admin/reset_pwd')}}" class="text-center" style="">忘记了你的密码?来重置一下</a>--}}

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{asset('AdminLTE-2.4.18/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- layer -->
<script src="{{asset('lib/layui/layui.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('AdminLTE-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('AdminLTE-2.4.18/plugins/iCheck/icheck.min.js')}}"></script>
<script>
	layui.use('layer', function(){
		var layer = layui.layer;
	});
	window.onkeyup = function(e){
		var ev = e || window.event;
		if(ev.keyCode == 13){
			document.getElementById("login").click();
		}
	};
	if ($(window).width()/7>=65) {
		$("html").css("font-size",$(window).width()/7+"px");
	}else{
		$("html").css("font-size","60px");
	}
	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' /* optional */
		});
	});
    $('#login').on("click",function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var user= $.trim($("input[name=user]").val());
        var password= $.trim($("input[name=password]").val());
        if(user === "" || password === ""){
            alert("请正确填写用户名以及密码");return;
        }
        $.ajax({
            url:"{{url('admin/login')}}",
            type:'POST',
            method:'put',
            data:{'user':user,'password':password},
            dataType:"JSON",
            traditional:true,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            success:function(datas){
                if(datas['code']===200){
                    layer.msg(datas['infor'], {time:1000},function () {
                        window.top.location.href="{{url('admin')}}";
                    })
                }else{
                    layer.msg(datas['infor'],{time:2000})
                }
            }//ALAX调用成功
            ,error:function (datas) {
                $("#code-mark").html('网络错误');
            }
        })
    })
</script>
</body>
</html>

