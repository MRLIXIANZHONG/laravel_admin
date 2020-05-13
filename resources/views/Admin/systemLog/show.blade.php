<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$Permission = new App\Models\Permission;
?>
@extends('Admin.base.popup')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="box-body">
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>操作用户[用户名|名字]</label>
                            <input type="text" class="form-control" value="{{ $info->user_id ? $info->user->username.'|'.$info->user->name : "未登录" }}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>请求地址|地址别名(权限)</label>
                            <input type="text" class="form-control" value="{{ $info->path }}|{{$Permission->getPermissionByName($info->path) ?$Permission->getPermissionByName($info->path)->display_name : '未记录权限'}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>请求参数</label>
                            <textarea rows="3" autoHeight="true" class="form-control">{{urldecode($info->params)}}</textarea>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>设备信息</label>
                            <textarea rows="3" class="form-control">{{ $info->agent }}</textarea>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>类型</label>
                            <input type="text" class="form-control" value="{{ $info->method }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>IP</label>
                            <input type="text" class="form-control" value="{{ $info->ip }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>IP详情</label>
                            <input type="text" class="form-control" value="{{ $info->ip_info }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>请求时间</label>
                            <input type="text" class="form-control" value="{{ $info->created_at }}">
                        </div>
                    </div>
                    <div class="box-footer" style="display: none;">
                        <input type="hidden" id="id_input_text" name="nm_input_text" />
                        <button type="submit" class="btn btn-primary" id="id_submit" name="nm_submit">提交修改</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
		$(function(){
			$('input,textarea,select').attr('disabled','disabled');
		});
        $.fn.autoHeight = function(){
            function autoHeight(elem){
                elem.style.height = 'auto';
                elem.scrollTop = 0; //防抖动
                elem.style.height = elem.scrollHeight + 'px';
            }
            this.each(function(){
                autoHeight(this);
                $(this).on('keyup', function(){
                    autoHeight(this);
                });
            });
        };
        $('textarea[autoHeight]').autoHeight();
    </script>
@endsection

