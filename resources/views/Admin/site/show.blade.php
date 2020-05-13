<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$areaInfo = (new \App\Models\Site3E21())->getArea3e21Info($info);
?>
@extends('Admin.base.popup')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body box-profile">
                    <div class="box-body">
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点名称</label>
                            <input type="text" class="form-control" name="site_name" value="{{$info->site_name}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>代理公司</label>
                            <input type="text" class="form-control" name="companyno" value="{{@$info->company->name ?? '无'}}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>网址</label>
                            <input type="text" class="form-control" name="weburl" value="{{$info->weburl}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点文件夹</label>
                            <input type="text" class="form-control" name="web_dir" value="{{$info->web_dir}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>城市类型</label>
                            <input type="text" class="form-control" name="city_type" value="{{$info->city_type}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>省</label>
                            <input type="text" class="form-control" name="sfid" value="{{isset($areaInfo[0]) ?
                            $areaInfo[0]->area : '无'}}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label>市</label>
                            <input type="text" class="form-control" name="dsid" value="{{isset($areaInfo[1]) ?
                            $areaInfo[1]->area : '无'}}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label>区县</label>
                            <input type="text" class="form-control" name="areano" value="{{isset($areaInfo[2]) ?
                            $areaInfo[2]->area : '无'}}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>城市名</label>
                            <input type="text" class="form-control" name="areatitle" value="{{$info->areatitle}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>logo</label>
                            <input type="text" class="form-control" name="logo" value="{{$info->logo}}">
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
                                   required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>加盟到期时间</label>
                            <input type="text" class="form-control" name="jfendtime" id="jfendtime" value="{{$info->jfendtime}}"
                                   required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>添加人</label>
                            <input type="text" class="form-control" name="addUsername" value="{{$info->addUsername}}">
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
                            <input type="text" class="form-control" name="spell" value="{{$info->spell}}" required>
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                            <label><i class="fa fa-check"></i>站点全拼</label>
                            <input type="text" class="form-control" name="initial" value="{{$info->initial}}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function(){
            $('input,textarea,select').attr('disabled','disabled');
        });
    </script>
@endsection
