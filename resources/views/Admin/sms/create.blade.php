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
                    <form role="form" action="{{url('admin/sms/create')}}" method="post" target="nm_iframe"
                          id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        {{csrf_field()}}
                        <div class="box-body">


                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>选择商家</label>
                                <select name="user_id" class="form-control" id="user_id">
                                    @foreach($site as $v)
                                        <option value="{{$v->siteid}}">{{$v->site_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="siteid" value="" id="siteid">
                            <input type="hidden" name="user_name" value="" id="user_name">

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>初始短信条数</label>
                                <input type="number" class="form-control" placeholder="初始短信条数" required name="msg_count">
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

            //查询下拉列表选择
           /* $("#user_id").click(function () {
               //console.log($(this).find('option:selected').data('site'))
                //$("#siteid").val($(this).find('option:selected').data('site'))
                //$("#user_name").val($(this).find('option:selected').data('name'))
            })*/


	    });
    </script>
@endsection
