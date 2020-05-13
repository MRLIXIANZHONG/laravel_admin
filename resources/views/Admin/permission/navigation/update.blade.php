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
                    <form role="form" action="{{url('admin/permission/navigation/update')}}" method="post"
                          target="nm_iframe" id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        <input type="hidden" name="id" value="{{$infor->id}}" >
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>父级导航</label>
                                <select name="parent_id" class="form-control">
                                    <option value="0">顶级导航</option>
                                    @foreach($parent as $v)
                                        <option @if($infor->parent_id == $v->id) selected @endif
                                                value="{{$v->id}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>导航名称</label>
                                <input type="text" class="form-control" placeholder="导航名称" required name="name"
                                       value="{{$infor->name}}">
                            </div>
                            <div class="form-group">
                                <label>导航地址</label>
                                <select name="permission_id" class="form-control">
                                    <option value="">不选择地址</option>
                                    @foreach($allPermissions as $v)
                                        <option @if($infor->permission_id == $v->id) selected @endif
                                        value="{{$v->id}}">{{$v->display_name ? "{$v->display_name}($v->name)" : $v->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>排序</label>
                                <input type="number" size="1" maxlength="4" minlength="" class="form-control"
                                       placeholder="排序,越小排名越前" name="sequence" value="{{$infor->sequence}}">
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="id_input_text" name="nm_input_text" />
                            <button type="submit" class="btn btn-primary" id="id_submit" name="nm_submit">提交添加</button>
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



