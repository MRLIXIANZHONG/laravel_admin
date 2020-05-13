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
    <title>{{ env('APP_NAME') ?: "后台管理端" }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/plugins/iCheck/flat/blue.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/dist/css/skins/skin-blue.min.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 3 -->
    <script src="{{asset('AdminLTE-2.4.18/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- layer -->
    <script src="{{asset('lib/layui/layui.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('AdminLTE-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('AdminLTE-2.4.18/dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('AdminLTE-2.4.18/plugins/iCheck/icheck.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- select2 -->
    <script src="{{asset('AdminLTE-2.4.18/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('AdminLTE-2.4.18/bower_components/select2/dist/css/select2.min.css')}}">
</head>
{{--操作按钮选中左右滑动效果--}}
<style>
    .z-switch{ width: 54px; height: 25px; position: relative; border: 1px solid #dfdfdf; background-color: #fdfdfd; box-shadow: #dfdfdf 0 0 0 0 inset; border-radius: 20px; background-clip: content-box; display: inline-block; -webkit-appearance: none; user-select: none; outline: none; }
    .z-switch:before { content: ''; display: block; width: 23px; height: 23px; position: absolute; top: 0; left: 0; border-radius: 20px; background-color: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);}
    .z-switch.switch-anim { transition: border cubic-bezier(0, 0, 0, 1) 0.4s, box-shadow cubic-bezier(0, 0, 0, 1) 0.4s; }
    .z-switch.switch-anim:before { transition: left 0.3s; }
    .z-switch.switch-anim.checked { box-shadow: #75b9e6 0 0 0 16px inset; background-color: #75b9e6; transition: border ease 0.4s, box-shadow ease 0.4s, background-color ease 1.2s;}
    .z-switch.switch-anim { transition: border cubic-bezier(0, 0, 0, 1) 0.4s, box-shadow cubic-bezier(0, 0, 0, 1) 0.4s;}
    .z-switch.checked { border-color: #75b9e6; box-shadow: #75b9e6 0 0 0 16px inset; background-color: #75b9e6;}
    .z-switch.checked:before { left: 30px; }

    /*select2*/
    .select2-container span.select2-selection--single{height: 34px;}
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
@include('Admin.base.header')
<!-- Left side column. contains the logo and sidebar -->
@include('Admin.base.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('title')
            </h1>
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
@include('Admin.base.footer')
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
	layui.use('layer', function(){
		var layer = layui.layer;
	});
    $(function(){
        $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
            var $parent = $(this).parent().addClass('active');
            $parent.siblings('.treeview.active').find('> a').trigger('click');
            $parent.siblings().removeClass('active').find('li').removeClass('active');
        });
        $('.sidebar-menu a').each(function(){
            if(this.href === window.location.protocol+"//"+window.location.host+window.location.pathname){
                $(this).parent().addClass('active')
                        .closest('.treeview-menu').addClass('.menu-open')
                        .closest('.treeview').addClass('active');
            }
        });
    });
</script>
@yield('script')
</body>
</html>
