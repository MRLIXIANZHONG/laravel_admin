<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.popup')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="box-body">
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>IP地址</label>
                            <input type="text" class="form-control" value="{{ $info->address }}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>是否限制</label>
                            <input type="text" class="form-control" value="{{ $info->iptype_text }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>最后访问路由</label>
                            <input type="text" class="form-control" value="{{ $info->atlastroute }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>访问次数</label>
                            <input type="text" class="form-control" value="{{ $info->accessnum }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>最后访问时间</label>
                            <input type="text" class="form-control" value="{{ $info->updated_at }}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>第一次访问时间</label>
                            <input type="text" class="form-control" value="{{ $info->created_at }}">
                        </div>
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
    </script>
@endsection
