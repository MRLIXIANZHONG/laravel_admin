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
                    <form role="form" action="{{url('admin/permission/route/update')}}" method="post" target="nm_iframe" id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        <input type="hidden" name="id" value="{{$permissionInfo->id}}" >
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group has-warning col-xs-12 col-sm-6 col-md-4">
                                <label><i class="fa fa-check"></i>名称</label>
                                <input type="text" class="form-control" placeholder="权限名称" required
                                       name="display_name" value="{{$permissionInfo->display_name}}">
                            </div>
                            <div class="form-group has-warning col-xs-12 col-sm-6 col-md-4">
                                <label><i class="fa fa-check"></i>路由地址</label>
                                <input type="text" class="form-control" placeholder="路由地址" required name="name" value="{{$permissionInfo->name}}">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-4">
                                <label>icon</label>
                                <input type="text" class="form-control" placeholder="icon-暂未使用" name="icon" value="{{$permissionInfo->icon}}">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-4">
                                <label>描述</label>
                                <input type="text" class="form-control" placeholder="权限描述" name="description" value="{{$permissionInfo->description}}">
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
    </script>
@endsection


