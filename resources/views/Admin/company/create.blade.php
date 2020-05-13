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
            <div class="box box-default">
                <div class="box-body">
                    <form role="form" method="post" target="nm_iframe" id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>公司名</label>
                                <input type="text" class="form-control" name="name" value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>公司地址</label>
                                <input type="text" class="form-control" name="add" value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>联系人</label>
                                <input type="text" class="form-control" name="liaison" value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>联系人电话</label>
                                <input type="text" class="form-control" name="liaison_phone" value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                <label>备注</label>
                                <textarea rows="3" class="form-control" name="remark"></textarea>
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
