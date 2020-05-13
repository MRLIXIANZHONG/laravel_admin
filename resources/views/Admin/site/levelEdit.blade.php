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
                    <form role="form" action="{{url('admin/site/site_level_edit')}}"  method="post" target="nm_iframe" id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >

                        <input type="hidden" name="id" value="{{$info->id}}">

                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>域名标识</label>
                                <input type="text" class="form-control" name="field_label" value="{{$info->field_label}}" required>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>顶级域名</label>
                                <input type="text" class="form-control" name="top_label" value="{{$info->top_label}}" required>
                            </div>

                            <div class="form-group has-warning">
                                <label><i class="fa fa-check"></i>系统</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="" >请选择系统</option>
                                    @foreach(site_level_type() as $k=>$v)
                                        <option value="{{$k}}" @if($info->type ==$k)selected="selected"@endif >{{$v}}</option>
                                     @endforeach
                                </select>
                            </div>

                            @if(getAuth()->siteid == \App\Models\User::SUPERADMIN)
                                <div class="form-group has-warning">
                                    <label><i class="fa fa-check"></i>是否开通</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="" >请选择</option>
                                        <option value="1" @if($info->status ==1)selected="selected"@endif >开通</option>
                                        <option value="0" @if($info->status ==0)selected="selected"@endif >未开通</option>
                                    </select>
                                </div>

                                <div class="form-group has-warning">
                                    <label><i class="fa fa-check"></i>过期时间</label>
                                    <input type="text" class="form-control" id="over_time" name="over_time" value="{{$info->over_time}}" required>
                                </div>

                            @endif

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
        layui.use(['laydate'],function () {
            var laydate = layui.laydate;
            // 添加时间
            laydate.render({
                elem: '#over_time', //指定元素
                type: 'datetime',
                trigger: 'click'
            });
            // 最后登录时间
            laydate.render({
                elem: '#jfendtime', //指定元素
                type: 'datetime',
                trigger: 'click'
            });
        })
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
