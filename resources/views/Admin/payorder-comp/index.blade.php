<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\Site3E21;
$getAuth = getAuth();
?>
@extends('Admin.base.blank')
@section('title','站点账户列表')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">站点账户列表</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                </div>
                <div class="box-body">
                    <form method="get" class="forms-sample"  target="search_iframe">
                        <div class="form-group col-md-1">
                            <input type="text" class="form-control search-btn" placeholder="站点ID"
                                   name="siteid"
                                   @if(!empty($_GET['siteid'])) value="{{$_GET['siteid'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-1">
                            <input type="text" class="form-control search-btn" placeholder="公司ID"
                                   name="companyno"
                                   @if(!empty($_GET['companyno'])) value="{{$_GET['companyno'] ?: ''}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索站点名" name="site_name"
                                   @if(!empty($_GET['site_name'])) value="{{$_GET['site_name']}}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="isjf" id="isjf">
                                <option value="">是否加盟[全部]</option>
                                @foreach((new Site3E21)->isjfLabel as $k=>$v)
                                    <option value="{{$k}}" @if(!empty($_GET['isjf']) and $_GET['isjf']==$k)
                                    selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/payorder-comp/index')}}" class="btn btn-default pull-right">
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
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header" style="background-color: #D3D4D3">
                    <h3 class="box-title">当前余额:<span style="color: red;">{{$count_sum->fund}}</span>元,</h3>
{{--这里数据可能有问题,不输出显示--}}
{{--                    &emsp;--}}
{{--                    <h3 class="box-title">收入总额:<span style="color: red;">{{$count_sum->totalprice}}</span>元,</h3>--}}
{{--                    &emsp;--}}
{{--                    <h3 class="box-title">支出总额:<span style="color: red;">{{$count_sum->totalprice-$count_sum->fund}}</span>元,</h3>--}}
                </div>
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$re->total()!!}</span>条</h3>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>站点编号</th>
                                <th>站点名称</th>
                                <th>加盟公司</th>
                                <th>账户余额</th>
                                <th>收入总额</th>
                                <th>支出总额</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{$v->siteid}}</td>
                                    <td>{{str_limit($v->site_name,25)}}</td>
                                    <td>{{str_limit(@$v->company->name,25)}}[{{@$v->company->id}}]</td>
                                    <td>{{$v->fund ?: 0}}</td>
                                    <td>{{$v->totalprice ?: 0}}</td>
                                    <td>{{$v->totalprice-$v->fund}}</td>
                                    <td>
                                        @if($getAuth->can('admin/payorder-comp/show-list'))
                                            <button type="button" class="btn btn-primary" title="查看详情" onClick="dataShow('{{url('admin/payorder-comp/show-list?siteid='.$v->siteid)}}')"><i class="fa fa-eye"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7">
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
    <script>
        //站点账户明细查看
        function dataShow(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"站点账户明细查看",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["95%","95%"],
                content:url
            })
        }
    </script>
@endsection
