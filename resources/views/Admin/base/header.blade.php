<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
<header class="main-header">

    <!-- Logo -->
    <a href="{{asset('/admin')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>{{env('APP_NAME')}}</b>ADMIN</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>{{env('APP_NAME')}}管理端</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">切换边框</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Notifications Menu -->
{{--                <li class="dropdown notifications-menu">--}}
{{--                    <!-- Menu toggle button -->--}}
{{--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                        <i class="fa fa-bell-o"></i>--}}
{{--                        <span class="label label-warning">10</span>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu">--}}
{{--                        <li class="header">You have 10 notifications</li>--}}
{{--                        <li>--}}
{{--                            <!-- Inner Menu: contains the notifications -->--}}
{{--                            <ul class="menu">--}}
{{--                                <li><!-- start notification -->--}}
{{--                                    <a href="#">--}}
{{--                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <!-- end notification -->--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                        <li class="footer"><a href="#">View all</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{getAuth()->avatar ? asset(getAuth()->avatar) : asset('AdminLTE-2.4.18/dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{getAuth()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{getAuth()->avatar ? asset(getAuth()->avatar) : asset('AdminLTE-2.4.18/dist/img/user2-160x160.jpg')}}" class="img-circle"
                            alt="User Image">
                            <p>
                                {{getAuth()->name}}
                                <small>会话持续进行,可点击"退出登录"结束会话</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            @if(getAuth()->can('admin/myself'))
                            <div class="pull-left">
                                <a href="{{url('admin/myself')}}" class="btn btn-default btn-flat">个人中心</a>
                            </div>
                            @endif

                            @if(getAuth()->can('admin/quit'))
                            <div class="pull-right">
                                <a href="javascript:void(0)" class="btn btn-default btn-flat" onClick="quit_btn()">退出登录</a>
                            </div>
                            @endif
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<script>
	//退出
	function quit_btn() {
		$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		$.ajax({
			url:"{{url('admin/quit')}}",
			type:'GET',
			data:{}, //传用户ID
			dataType:"JSON",
			traditional:true,
			contentType: "application/x-www-form-urlencoded; charset=utf-8",
			success:function(datas){
				if(datas['code']===200){
					layer.msg("退出登陆成功，已清除用户信息", {time:2000},function () {
						window.location.href = '{{url('admin/login')}}';
					})
				}else{
					layer.msg(datas['infor'],{time:2000})
				}
			}//ALAX调用成功
			,error:function (datas) {
				$("#code-mark").html('网络错误');
			}
		})
	}
</script>
