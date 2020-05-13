<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\TPayOrderMain;
$getAuth = getAuth();
$yeay = date('Y');
?>
@extends('Admin.base.blank')
@section('title','账户明细查看')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">账户明细查看</li>
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
                        <input type="hidden" name="siteid" value="{{$siteid}}">
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="PayType" id="PayType">
                                <option value="">业务线[全部]</option>
                                @foreach($payType as $v)
                                    <option value="{{$v->id}}" @if(!empty($_GET['PayType']) and
                                    $_GET['PayType']==$v->id) selected="selected" @endif>{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="PayWay" id="PayWay">
                                <option value="">支付方式[全部]</option>
                                @foreach((new TPayOrderMain)->PayWayLabel as $k=>$v)
                                    <option value="{{$k}}" @if(!empty($_GET['PayWay']) and
                                    $_GET['PayWay']==$k) selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="year" id="year">
                                <option value="">选择年[全部]</option>
                                <option value="{{$yeay}}" @if(!empty($_GET['year']) and$_GET['year']==$yeay)
                                selected="selected" @endif>{{$yeay}}年</option>
                                <option value="{{$yeay-1}}" @if(!empty($_GET['year']) and$_GET['year']==$yeay-1)
                                selected="selected" @endif>{{$yeay-1}}年</option>
                                <option value="{{$yeay-2}}" @if(!empty($_GET['year']) and$_GET['year']==$yeay-2)
                                selected="selected" @endif>{{$yeay-2}}年</option>
                                <option value="{{$yeay-3}}" @if(!empty($_GET['year']) and$_GET['year']==$yeay-3)
                                selected="selected" @endif>{{$yeay-3}}年</option>
                                <option value="{{$yeay-4}}" @if(!empty($_GET['year']) and$_GET['year']==$yeay-4)
                                selected="selected" @endif>{{$yeay-4}}年</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="month" id="month">
                                <option value="">选择月[全部]</option>
                                @for($i = 1;$i<=12;$i++)
                                    <option value="{{$i}}" @if(!empty($_GET['month']) and$_GET['month']==$i)
                                    selected="selected" @endif>{{$i}}月</option>
                                @endfor
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
                        <a href="{{url('admin/payorder-comp/show-list?siteid='.$siteid)}}" class="btn btn-default pull-right"><i class="fa fa-trash-o"></i>清空检索条件
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
                    <h3 class="box-title">当前余额:<span style="color: red;">{{@$agent->fund ?: '暂无'}}</span>元,</h3>
                    &emsp;
                    <h3 class="box-title">收入总额:<span style="color: red;">{{@$count_sum['income']}}</span>元,</h3>
                    &emsp;
                    <h3 class="box-title">支出总额:<span style="color: red;">{{@$count_sum['unincome']}}</span>元,</h3>
                </div>
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$re->total()!!}</span>条</h3>
                </div>
                <div class="box-body" id="data-list">
                    @if($re->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>流水号</th>
                                <th>站点(公司编号)</th>
                                <th>业务线</th>
                                <th>名称备注</th>
                                <th>来源类型</th>
                                <th>支付方式</th>
                                <th>订单金额</th>
                                <th>接口比例</th>
                                <th>接口使用费</th>
                                <th>提成比例</th>
                                <th>提成金额</th>
                                <th>实际到账</th>
                                <th>订单时间</th>
                                <th>操作</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($re as $k=>$v)
                                <tr>
                                    <td>{{$v->mOrderID}}</td>
                                    <td>{{str_limit(@$v->TPayOrderMain->site3E21->site_name,25)}}_{{str_limit(@$v->TPayOrderMain->company->name,25)}}[{{@$v->company->id}}]</td>
                                    <td>{{str_limit(@$v->tPayType->name,25)}}</td>
                                    <td>{{$v->AttachInfo}}</td>
                                    <td>{{$v->Isincome_text}}</td>
                                    <td>{{$v->PayWay_text}}</td>
                                    <td>{{$v->Price}}</td>
                                    <td>{{$v->CostRate}}</td>
                                    <td>{{$v->CostMoney}}</td>
                                    <td>{{$v->RoyaltyRate}}</td>
                                    <td>{{$v->RoyaltyMoney}}</td>
                                    <td>{{$v->SPrice}}</td>
                                    <td>{{$v->PayTime}}</td>
                                    <td>
                                        @if($getAuth->can('admin/payorder-comp/show-detail'))
                                            <button type="button" class="btn btn-primary" title="订单详情" onClick="dataShow('{{url('admin/payorder-comp/show-detail?mOrderID='.$v->mOrderID)}}')"><i class="fa fa-eye"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="14">
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
        //订单详情查看
        function dataShow(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"订单详情查看",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["60%","60%"],
                content:url
            })
        }
    </script>
@endsection
