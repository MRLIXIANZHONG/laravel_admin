<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.popup')
@section('content')
    <style type="text/css">
        .content_text{
            float: right;
            margin-right: 62px;
            text-align: left;
            width: 250px;
        }


    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">

                        <input type="hidden" name="_method" value="PUT" >
                        {{csrf_field()}}
                        <div class="box-body">



                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>提现账号:</label>
                                <div class="content_text">{{$data->UserName}}/div>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>所属站点:</label>
                                <div class="content_text">{{$data->site_name}}</div>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>提现金额:</label>
                               <div class="content_text"> {{$data->ApplyMoney}}元     打款金额：{{$data->PayMoney}}元</div>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>交易手续费:</label>
                                <div class="content_text">{{$data->CutHead + $data->CutAlly}}（盟友收{{$data->CutHead}}元，系统收{{$data->CutAlly}}元）</div>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>申请时间:</label>
                                <div class="content_text">{{$data->ApplyTime}}</div>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>流水是否异常:</label>
                                <div class="content_text" style="color: red">{{$re['statue']}}</div>
                            </div>


                        </div>
                        <div class="box-footer" style="text-align: center">
                            <input type="hidden" id="id_input_text" name="nm_input_text" />
                            <button type="submit" class="btn btn-pinterest" onclick="tixian('{{$data->Id}}',1,'{{url('admin/usercarry/operation')}}')" >拒绝提现</button>
                            <button type="submit" class="btn btn-primary"  onclick="tixian('{{$data->Id}}',2,'{{url('admin/usercarry/operation')}}')" style="margin-left: 50px;">确认打款</button>
                        </div>

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

	    //提现操作
	    function tixian(id,utype,url) {
            $.post(url,{_token:"{{csrf_token()}}",id:id,utype:utype},function (data) {
                if(data['code']===200){
                    layer.msg(data['infor'], {time:2000},function () {
                        parent.window.location.reload();
                    })
                }else{
                    layer.msg(data['infor'],{time:2000})
                }
            })
        }


    </script>
@endsection
