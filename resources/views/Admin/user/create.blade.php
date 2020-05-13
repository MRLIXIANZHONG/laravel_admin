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
                    <form role="form" action="{{url('admin/user/create')}}" method="post" target="nm_iframe"
                          id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>用户名</label>
                                <input type="text" class="form-control" placeholder="用户名" required name="username">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>姓名</label>
                                <input type="text" class="form-control" placeholder="姓名" required name="name">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>邮箱</label>
                                <input type="email" class="form-control" placeholder="邮箱" required name="email">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>绑定联盟</label>
                                <select name="siteid" id="siteid" class="form-control">
                                    @foreach($site as $v)
                                        <option value="{{$v->siteid}}" name="siteid">{{$v->site_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>角色</label>
                                <select name="role" class="form-control">
                                    @if(getAuth()->siteid !== \App\Models\User::SUPERADMIN)
                                        <option value="站点管理员">站点管理员</option>
                                    @else
                                        @foreach($role as $v)
                                        <option value="{{$v->name}}">{{$v->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>密码</label>
                                <input type="password" class="form-control" placeholder="密码不填写默认123456" name="password">
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
