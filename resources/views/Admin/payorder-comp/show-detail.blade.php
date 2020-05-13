<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
use App\Models\TPayOrderComp;
?>
@extends('Admin.base.popup')
@section('content')
    @if(@$info->id)
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list-ul"></i>主订单信息</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>主订单号</label>
                            <input type="text" class="form-control" value="{{$info->id}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>订单描述</label>
                            <input type="text" class="form-control" value="{{$info->AttachInfo}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>订单时间</label>
                            <input type="text" class="form-control" value="{{$info->PayTime}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>支付方式</label>
                            <input type="text" class="form-control" value="{{$info->PayWay_text}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>支付金额</label>
                            <input type="text" class="form-control" value="{{$info->Price}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>支付流水号</label>
                            <input type="text" class="form-control" value="{{$info->OverInfo}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>支付时间</label>
                            <input type="text" class="form-control" value="{{$info->PayTime}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(@$info->comppid)
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list-ul"></i>子订单信息</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>子订单号</label>
                            <input type="text" class="form-control" value="{{$info->comppid}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>来源类型</label>
                            <input type="text" class="form-control" value="{{@(new TPayOrderComp())->IsincomeLabel[$info->compIsincome]}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>收款盟友</label>
                            <input type="text" class="form-control" value="{{$info->compuid}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>接口使用费{{$info->compCostRate}}%</label>
                            <input type="text" class="form-control" value="{{$info->compCostMoney}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>功能使用费{{$info->compRoyaltyRate}}</label>
                            <input type="text" class="form-control" value="{{$info->compRoyaltyMoney}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>实际到账</label>
                            <input type="text" class="form-control" value="{{$info->compSPrice}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(@$info->userid)
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list-ul"></i>子订单信息</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>子订单号</label>
                            <input type="text" class="form-control" value="{{$info->userid}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>来源类型</label>
                            <input type="text" class="form-control" value="{{@(new TPayOrderComp())->IsincomeLabel[$info->userIsincome]}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>收款盟友</label>
                            <input type="text" class="form-control" value="{{$info->useruid}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>接口使用费{{$info->userCostRate}}%</label>
                            <input type="text" class="form-control" value="{{$info->userCostMoney}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>功能使用费{{$info->userRoyaltyRate}}</label>
                            <input type="text" class="form-control" value="{{$info->userRoyaltyMoney}}">
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label>实际到账</label>
                            <input type="text" class="form-control" value="{{$info->userSPrice}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
@section('script')
    <script>
        $(function(){
            $('input,textarea,select').attr('disabled','disabled');
        });
        $.fn.autoHeight = function(){
            function autoHeight(elem){
                elem.style.height = 'auto';
                elem.scrollTop = 0; //防抖动
                elem.style.height = elem.scrollHeight + 'px';
            }
            this.each(function(){
                autoHeight(this);
                $(this).on('keyup', function(){
                    autoHeight(this);
                });
            });
        };
        $('textarea[autoHeight]').autoHeight();
    </script>
@endsection

