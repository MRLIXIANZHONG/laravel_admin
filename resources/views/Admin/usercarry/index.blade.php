<?php

use App\Models\User;
?>
@extends('Admin.base.blank')
@section('title','用户提现管理')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">用户提现管理</li>
@endsection
<link href="{{asset('/lib/laydate/theme/laydate.css')}}">
<script src="{{asset('/lib/laydate/laydate.js')}}"></script>
@section('content')
    @if(getAuth()->siteid == \App\Models\User::SUPERADMIN)
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                </div>
                <div class="box-body">
                    <form method="get" class="forms-sample"  target="search_iframe">

                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="State" id="State">
                                <option value="-1">全部状态</option>
                                <option value="0" @if(!empty($_GET['State']) and $_GET['State']==0)selected="selected" @endif >待审核</option>
                                <option value="1" @if(!empty($_GET['State']) and $_GET['State']==1)selected="selected" @endif >已通过</option>
                                <option value="2" @if(!empty($_GET['State']) and $_GET['State']==2)selected="selected" @endif >已拒绝</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="站点名称" name="site_name"
                                   @if(!empty($_GET['site_name'])) value="{{$_GET['site_name']}}" @endif>
                        </div>

                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="交易单号" name="OrderNum"
                                   @if(!empty($_GET['OrderNum'])) value="{{$_GET['OrderNum']}}" @endif>
                        </div>

                        <div class="form-group col-md-2">
                            <input type="text" id="wlaydate" class="form-control form-control-sm" placeholder="检索时间段" autocomplete="off">
                            <input type="hidden" name="date1" id="date1" @if(!empty($_GET['date1'])) value="{{$_GET['date1']}}" @endif>
                            <input type="hidden" name="date2" id="date2" @if(!empty($_GET['date2'])) value="{{$_GET['date2']}}" @endif>
                        </div>

                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/capital/index')}}" class="btn btn-default pull-right">
                            <i class="fa fa-trash-o"></i>清空检索条件
                        </a>
                        <div style="display: none;">
                            <input type="submit" class="btn btn-primary" value="搜索">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$re->total()!!}</span>条</h3>
                    <div class="pull-right mt10">

                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>站点</th>
                                <th>用户账号</th>
                                <th>提现金额</th>
                                <th>打款金额</th>
                                <th>用户余额</th>
                                <th>交易单号</th>
                                <th>申请时间</th>
                                <th>操作时间</th>
                                <th>审核状态</th>
                                <th>操作</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{$v->Id}}</td>
                                    <td>{{str_limit($v->site_name,25)}}</td>
                                    <td>{{str_limit($v->username,25)}}</td>
                                    <td>{{$v->ApplyMoney}}</td>
                                    <td>{{$v->PayMoney}}</td>
                                    <td>{{$v->balance}}</td>
                                    <td>{{$v->OrderNum}}</td>
                                    <td>{{$v->ApplyTime}}</td>
                                    <td>{{$v->UpdateTime}}</td>

                                   @if($v->State ==0)
                                    <td style="color:silver">待审核</td>
                                    @elseif($v->State == 1)
                                    <td style="color:green">已通过</td>
                                    @else
                                        <td style="color:red">已拒绝</td>
                                    @endif


                                    <td>

                                           {{-- <button type="button" class="btn btn-primary" title="修改" onClick="dataEdit('{{url('admin/sms/update?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>--}}
                                           {{-- <button type="button" class="btn btn-info" title="充值" onClick="dataEdit('{{url('admin/sms/recharge?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger" title="删除" onClick="del('{{url('admin/sms/delete?id='.$v->id)}}')"><i class="fa fa-edit"></i></button>--}}

                                           {{-- <button  @if(getAuth()->siteid == User::SUPERADMIN) disabled @endif type="button" class="btn btn-success" title="提现" onClick="carray('{{url('admin/capital/carry?id='.$v->id)}}')" ><i class="fa fa-edit"></i></button>--}}
                                            <button @if($v->State !=0) disabled @endif  type="button" class="btn btn-success" title="操作" onClick="carray('{{url('admin/usercarry/popup?id='.$v->Id)}}')" ><i class="fa fa-edit"></i></button>
                                            <button  type="button" class="btn btn-info" title="流水" onClick="carray2('{{url('admin/usercarry/water?id='.$v->Id)}}')" ><i class="fa fa-edit"></i></button>

                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6">
                                    {!! $re->render('Admin.base.custom') !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>404!</strong> 当前列表暂无数据.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <iframe id="search_iframe" name="search_iframe" style="display:none;"></iframe>
@endsection
@section('script')
    <script src="{{asset('lib/admin/admin.automatic.search.js')}}"></script>
    <script src="{{asset('lib/admin/admin.automatic.search.date.js')}}"></script>
    <script>
        //信息修改
        function dataEdit(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"信息修改",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["80%","80%"],
                content:url
            })
        }
        //弹窗1
        function carray(url){
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"用户提现审核",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["30%","50%"],
                content:url
            })
        }
        function carray2(url) {
            location.href=url;
        }

    </script>
@endsection
