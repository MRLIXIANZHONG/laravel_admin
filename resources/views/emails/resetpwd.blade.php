<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
<body style="color: #666; font-size: 14px; font-family: 'Open Sans',Helvetica,Arial,sans-serif;">
<div class="box-content" style="width: 80%; margin: 20px auto; max-width: 800px; min-width: 600px;">
    <div class="header-tip" style="font-size: 12px;
                                   color: #aaa;
                                   text-align: right;
                                   padding-right: 25px;
                                   padding-bottom: 10px;">
        {{env('APP_NAME')}} - 你正在请求平台密码修改,请勿将该邮件转发他人
    </div>
    <div class="info-top" style="padding: 15px 25px;
                                 border-top-left-radius: 10px;
                                 border-top-right-radius: 10px;
                                 background: #0b58a2;
                                 color: #fff;
                                 overflow: hidden;
                                 line-height: 32px;">
        <img src="{{asset('/admin_asset/img/reserPwd.webp')}}" style="float: left; margin: 0 10px 0 0; width: 32px;" /><div
            style="color:#010e07"><strong>{{env('APP_NAME')}}管理端密码修改</strong></div>
    </div>
    <div class="info-wrap" style="border-bottom-left-radius: 10px;
                                  border-bottom-right-radius: 10px;
                                  border:1px solid #ddd;
                                  overflow: hidden;
                                  padding: 15px 15px 20px;">
        <div class="tips" style="padding:15px;">
            <p style=" list-style: 160%; margin: 10px 0;">Hi,{{$res['name']}}</p>
            <p style=" list-style: 160%; margin: 10px 0;">
                你正在请求平台密码修改,如确认是你本人操作,请点击以下链接进行密码修改操作
            </p>
            <p>
                <a href="{{url('admin/execute/reset_pwd?token='.$res['token'])}}">点击此链接去修改密码[{{env('APP_NAME')}}|本链接10分钟内有效]</a>
            </p>
        </div>
        <div class="time" style="text-align: right; color: #999; padding: 0 15px 15px;">{{date('Y-m-d H:i:s',time())}}</div>
        <br>
    </div>
</div>
</body>
