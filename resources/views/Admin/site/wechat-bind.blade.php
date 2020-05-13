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
            <div>
                <div class="box-body box-profile">
                    <img src="{{$sideUrl}}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
	    $(function(){
            setInterval("getwechatBind()",4000);
	    });
	    function getwechatBind() {
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            $.ajax({
                type : 'get',
                url: "{{url('admin/get-wechat-bind')}}",
                dataType:"json",
                success:function (datas) {
	                if(datas['code']===200){
                        layer.msg(datas['infor'], {time:2000},function () {
                            parent.window.location.reload();
                        })
                    }else{
                        //layer.msg(datas['infor'],{time:2000})
                    }
                }
            });
        }
    </script>
@endsection
