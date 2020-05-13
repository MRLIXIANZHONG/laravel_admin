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
                <div class="box-body box-profile">
                    <form role="form" method="post" target="nm_iframe" id="myform" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT" >
                        <input type="hidden" name="siteid" value="{{$info->siteid}}" >
                        {{csrf_field()}}
                    <div class="box-body">
                        @php $reonly = getAuth()->siteid !== \App\Models\User::SUPERADMIN ? 'readonly' : ''; @endphp
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点名称</label>
                            <input type="text" class="form-control" name="site_name" value="{{$info->site_name}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>代理公司</label>
                            <select name="companyno" class="form-control" required {{$reonly}}>
                                @foreach($company as $v)
                                    <option value="{{$v->id}}" @if($info->companyno == $v->id) selected  @endif>{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>网址</label>
                            <input type="text" class="form-control" name="weburl" value="{{$info->weburl}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点文件夹</label>
                            <input type="text" class="form-control" name="web_dir" value="{{$info->web_dir}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>城市类型</label>
                            <input type="text" class="form-control" name="city_type" value="{{$info->city_type}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>省</label>
                            <input type="text" class="form-control" name="sfid" value="{{$info->sfid}}" required {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label>市</label>
                            <input type="text" class="form-control" name="dsid" value="{{$info->dsid}}" required {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label>区县</label>
                            <input type="text" class="form-control" name="areano" value="{{$info->areano}}" required {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>城市名</label>
                            <input type="text" class="form-control" name="areatitle" value="{{$info->areatitle}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>logo</label>
                            <input type="text" class="form-control" name="logo" value="{{$info->logo}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>简介文本</label>
                            <textarea rows="4" class="form-control" name="logotxt">{{$info->logotxt}}</textarea>
                        </div>
{{--                        <div class="form-group col-xs-12 col-sm-6 col-md-4">--}}
{{--                            <label>备注文本</label>--}}
{{--                            <textarea rows="4" class="form-control" name="memo">{{$info->memo}}</textarea>--}}
{{--                        </div>--}}
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>网站标题</label>
                            <input type="text" class="form-control" name="title" value="{{$info->title}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>加盟时间</label>
                            <input type="text" class="form-control" name="jftime" id="jftime" value="{{$info->jftime}}"
                                   required {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>加盟到期时间</label>
                            <input type="text" class="form-control" name="jfendtime" id="jfendtime" value="{{$info->jfendtime}}"
                                   required {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>添加人</label>
                            <input type="text" class="form-control" name="addUsername" value="{{$info->addUsername}}" {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>工作QQ</label>
                            <input type="text" class="form-control" name="qq" value="{{$info->qq}}">
                        </div>
                        @if(getAuth()->siteid == \App\Models\User::SUPERADMIN)
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>联系备注</label>
                            <input type="text" class="form-control" name="linkmemo" value="{{$info->linkmemo}}">
                        </div>
                        @endif
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点首字母</label>
                            <input type="text" class="form-control" name="spell" value="{{$info->spell}}" required {{$reonly}}>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点全拼</label>
                            <input type="text" class="form-control" name="initial" value="{{$info->initial}}" required {{$reonly}}>
                        </div>

                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>新老站点选择</label>
                            <select name="is_new" id="is_new" class="form-control" required>
                                <option value="1" @if($info->is_new == 1)selected="selected"@endif >新站点</option>
                                <option value="0" @if($info->is_new == 0)selected="selected"@endif >老站点</option>
                            </select>
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
	    layui.use(['laydate'],function () {
		    var laydate = layui.laydate;
		    // 添加时间
		    laydate.render({
			    elem: '#jftime', //指定元素
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
