<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.blank')
@section('title','redis重置')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">redis重置</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-warning"></i>

                    <h3 class="box-title">说明</h3>
                </div>
                <div class="box-body">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-info"></i> Redis缓存清理请勿在正式项目高峰使用时段</h4>
                        系统重置当前项目内所有redis数组
                    </div>
                </div>
            </div>
            <a style="width: 20%;" href="javascript:void(0)" onClick="redisRenew()"
               class="btn btn-primary btn-block">重置Redis</a>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('lib/admin/admin.automatic.search.js')}}"></script>
    <script>
			function redisRenew() {
				layer.confirm('确定重置redis?',{btn:['确定','取消']},function () {
					$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
					$.ajax({
						type : 'get',
						url: "{{url('admin/system-redis/renew')}}",
                        method : 'put',
						dataType:"json",
						success:function (datas) {
							layer.msg(datas['infor'], {time:2000});
						}
					});
				});
			}
    </script>
@endsection

