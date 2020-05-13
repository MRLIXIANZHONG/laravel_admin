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
    <title>重置密码</title>
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
    <div class="lockscreen-logo">
        <a href="{{url('/admin')}}"><b>{{env('APP_NAME')}}</b>管理端</a>
    </div>
    <!-- User name -->
    <div class="lockscreen-name">在这里进行密码重置|请记住修改后的密码</div>

    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">

        <!-- lockscreen credentials (contains the form) -->
        <form>
            <div class="box-body">
                <div class="form-group has-warning">
                    <label><i class="fa fa-check"></i>密码</label>
                    <input type="password" class="form-control" placeholder="输入新密码" required name="password">
                </div>
                <div class="form-group has-warning">
                    <label><i class="fa fa-check"></i>确认密码</label>
                    <input type="password" class="form-control" placeholder="确认新密码" required name="repassword">
                </div>
            </div>
            <div class="box-footer">
                <button type="button" class="btn btn-primary" id="reset">提交修改</button>
            </div>
        </form>
        <!-- /.lockscreen credentials -->

    </div>
    <div class="text-center">
        <a href="{{url('admin/login')}}">又想起密码了？回到后台登录</a>
    </div>
    @include('Admin.base.footer')
</div>
<!-- /.center -->

<!-- jQuery 3 -->
<script src="{{asset('AdminLTE-2.4.18/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- layer -->
<script src="{{asset('lib/layui/layui.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('AdminLTE-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script>
	document.getElementById("main-footer").classList.remove("main-footer");
	layui.use('layer', function(){
		var layer = layui.layer;
	});
	window.onkeyup = function(e){
		var ev = e || window.event;
		if(ev.keyCode == 13){
			document.getElementById("reset").click();
		}
	};
	$('#reset').on("click",function() {
		$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		var password= $.trim($("input[name=password]").val());
        var repassword= $.trim($("input[name=repassword]").val());
        var email = "{{$email}}";
		if(password === "" || repassword === ""){
			alert("请填写你的新密码并确认");return;
		}
		$.ajax({
			url:"{{url('admin/execute/reset_pwd')}}",
			type:'POST',
			method:'put',
			data:{'password':password,'repassword':repassword,'email':email},
			dataType:"JSON",
			traditional:true,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			success:function(datas){
				if(datas['code']===200){
					layer.msg(datas['infor'], {time:1000},function () {
						window.top.location.href="{{url('admin/login')}}";
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
