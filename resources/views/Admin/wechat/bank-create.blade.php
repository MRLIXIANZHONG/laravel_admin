<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$TpayModel = new App\Models\Tpay();
?>
@extends('Admin.base.popup')
@section('content')
    <form role="form" method="post" target="nm_iframe" id="myform" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT" >
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-list-ul"></i>基本信息部分</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>公司名</label>
                                <input type="text" class="form-control" placeholder="公司名" name="compname"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>所属站点</label>
                                <select name="siteid" id="siteid" class="form-control" required>
                                    @foreach($site as $v)
                                        <option value="{{$v->siteid}}" name="siteid">{{$v->site_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-list-ul"></i>支付宝PC扫码支付配置部分</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝PC扫码</label>
                                <select name="isali" id="isali" class="form-control" required>
                                    @foreach($TpayModel->isaliLabel as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝账号</label>
                                <input type="text" class="form-control" placeholder="支付宝账号" name="alipay1"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝appid</label>
                                <input type="text" class="form-control" placeholder="支付宝appid" name="alipay2"
                                       value="" required>
                            </div><div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>商户支付密钥</label>
                                <input type="text" class="form-control" placeholder="商户支付密钥" name="alipay3"
                                       value="" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-list-ul"></i>支付宝手机网页支付配置部分</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝手机网页</label>
                                <select name="isaliphone" id="isaliphone" class="form-control" required>
                                    @foreach($TpayModel->isaliphoneLabel as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝账号</label>
                                <input type="text" class="form-control" placeholder="支付宝账号" name="alipayphone1"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝appid</label>
                                <input type="text" class="form-control" placeholder="支付宝appid" name="alipayphone2"
                                       value="" required>
                            </div><div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付密钥</label>
                                <input type="text" class="form-control" placeholder="支付密钥" name="alipayphone3"
                                       value="" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-list-ul"></i>支付宝APP支付配置部分</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝APP</label>
                                <select name="IsAlipayApp" id="IsAlipayApp" class="form-control" required>
                                    @foreach($TpayModel->IsAlipayAppLabel as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝账号</label>
                                <input type="text" class="form-control" placeholder="支付宝账号" name="AlipayApp1"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>支付宝appid</label>
                                <input type="text" class="form-control" placeholder="支付宝appid" name="AlipayApp2"
                                       value="" required>
                            </div><div class="form-group col-xs-12 col-sm-6 col-md-6 has-warning">
                                <label><i class="fa fa-check"></i>私钥</label>
                                <input type="text" class="form-control" placeholder="支付密钥" name="AlipayApp3"
                                       value="" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-list-ul"></i>微信支付配置部分</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                                <label><i class="fa fa-check"></i>是否开通微信支付</label>
                                <select name="isWx" id="isWx" class="form-control" required>
                                    @foreach($TpayModel->isWxLabel as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div><div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                                <label><i class="fa fa-check"></i>微信公众平台appid</label>
                                <input type="text" class="form-control" placeholder="微信公众平台appid" name="wxAppID"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                                <label><i class="fa fa-check"></i>微信公众平台secret</label>
                                <input type="text" class="form-control" placeholder="微信公众平台secret" name="wxAppSecret"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                                <label><i class="fa fa-check"></i>商户平台商户号</label>
                                <input type="text" class="form-control" placeholder="商户平台商户号" name="wxMchID"
                                       value="" required>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-4 has-warning">
                                <label><i class="fa fa-check"></i>商户平台商户key</label>
                                <input type="text" class="form-control" placeholder="商户平台商户key" name="wxKey"
                                       value="" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="hidden" id="id_input_text" name="nm_input_text" />
            <button type="submit" class="btn btn-primary" id="id_submit" name="nm_submit">提交添加</button>
        </div>
    </form>
    <iframe id="id_iframe" name="nm_iframe" style="display:none;"></iframe>
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

