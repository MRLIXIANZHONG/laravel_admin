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
    <title>以邮箱重置密码</title>
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
    <div class="lockscreen-name">密码重置</div>

    <!-- START LOCK SCREEN ITEM -->
    <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
            <img src="{{asset('AdminLTE-2.4.18/dist/img/user1-128x128.jpg')}}" alt="User Image">
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials">
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="邮箱" required>

                <div class="input-group-btn">
                    <button type="button" class="btn" id="reset"><i class="fa fa-arrow-right text-muted"></i></button>
                </div>
            </div>
        </form>
        <!-- /.lockscreen credentials -->

    </div>
    <!-- /.lockscreen-item -->
    <div class="help-block text-center">
        请输入你的邮箱地址
    </div>
    <div class="text-center">
        <a href="{{url('admin/login')}}">又想起密码了？回到后台登录</a>
    </div>
    <div style="clear: none;">
        @include('Admin.base.footer')
    </div>
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
	    var loadix = layer.load(1, {shade: [0.1,'#fff']});
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var email= $.trim($("input[name=email]").val());
        if(email === ""){
            alert("请正确填写你的邮箱地址");return;
        }
        $.ajax({
            url:"{{url('admin/reset_pwd')}}",
            type:'POST',
            method:'put',
            data:{'email':email},
            dataType:"JSON",
            traditional:true,
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            success:function(datas){
	            layer.close(loadix);
                if(datas['code']===200){
                    layer.msg(datas['infor'], {time:1000},function () {
                        window.top.location.href="{{url('/admin')}}";
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

