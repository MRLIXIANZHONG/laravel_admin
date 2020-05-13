<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.blank')
@section('title','微信配置')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">微信配置</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <form role="form" action="{{url('admin/wechat/system/index')}}" method="post" target="nm_iframe"
                          id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>app_id</label>
                                <input type="text" class="form-control" placeholder="app_id" required name="app_id" value="{{$detail['app_id']}}">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>secret</label>
                                <input type="text" class="form-control" placeholder="secret" required name="secret" value="{{$detail['secret']}}">
                            </div>
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>token</label>
                                <input type="text" class="form-control" placeholder="token" required name="token" value="{{$detail['token']}}">
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>aes_key</label>
                                <input type="text" class="form-control" placeholder="aes_key" required name="aes_key" value="{{$detail['aes_key']}}">
                            </div>

                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="id_input_text" name="nm_input_text" />
                            <button type="submit" class="btn btn-primary" id="id_submit" name="nm_submit">更新</button>
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
