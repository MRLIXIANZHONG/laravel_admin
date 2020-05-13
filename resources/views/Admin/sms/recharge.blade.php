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
                    <form role="form" action="{{url('admin/sms/recharge')}}" method="post" target="nm_iframe"
                          id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        {{csrf_field()}}
                        <div class="box-body">

                            <input type="hidden" name="id" value="{{$data['id']}}">

                            <div class="form-group has-warning">
                                <label>充值条数(不得低于1000条)</label>
                                <input type="text" class="form-control" placeholder="充值短信条数" required name="msg_count" id="msg_count">
                            </div>

                            <div class="form-group has-warning">
                                <label>单价</label>
                                <b style="color: red">0.08元/条</b>
                            </div>

                            <div class="form-group has-warning">
                                <label>备注</label>
                               <textarea name="remarks" placeholder="填写备注" class="form-control"></textarea>
                            </div>


                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="id_input_text" name="nm_input_text" />
                            <button type="submit" class="btn btn-primary" id="id_submit" name="nm_submit">充值</button>
                            <b style="color: red;font-size: 5px;">共计消费：<em id="money">0</em>元，点击提交后将直接扣除后台余额</b>
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
						   // parent.window.location.reload();
						  //location.href= + datas['url'];
                          parent.window.location.reload();
						  window.open(datas['url']);
					    })
				    }else{
					    layer.msg(datas['infor'],{time:2000})
				    }
			    }
		    })

            //验证条数和技术值
            $("#msg_count").blur(function () {
                var number=$(this).val();
                re = new RegExp("^[0-9]*[1-9][0-9]*$");
                if (!re.test(number)){
                    $(this).val('');
                    layer.msg('请输入数字',{time:2000})
                    return false;
                }
                if (number < 1000){
                    $(this).val('');
                    layer.msg('充值短信不得低于1000条',{time:2000})

                    return false;
                }

                //更改总价
                 var money=$(this).val() * 0.08;
                $("#money").text(money);

            })



	    });
    </script>
@endsection
