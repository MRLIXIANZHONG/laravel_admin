<?php
/**
 * 全局方法
 * Created by Lxd.
 * QQ: 790125098
 */

/**
 * 每页显示数量
 * Created by Lxd
 * @return array
 */
function getSizeArr(){
    return [20,30,50,70,100];
}

/**
 * 登录认证用户信息获取
 * Created by Lxd
 * @return mixed
 */
function getAuth(){
    return \Illuminate\Support\Facades\Auth::guard('admin_user')->user();
}

/**
 * Created by Lxd
 * @return false|string
 */
function getDateTime(){
    return date('Y-m-d H:i:s');
}

//支付
function GetNewPayUrl($id,$uid,$uType,$touid1,$toutype1,$touid2,$toutype2,$money,$u1price,$u2price,$payType,$siteid,$isyue,$wx_uid,$payName,$pay,$order_sn,$thorough=0){
    $code = "";//1001 不支持微信支付 1002不支持支付宝 1003不支持财付通 1004不支持网银在线
    $timeStamp=time();
    $sign = md5($money.$id.$uid.$timeStamp."paywayccoocity2016");
    $param = 'id='.$id.'&uid='.$uid.'&uty='.$uType.'&p='.$money.'&u1p='.$u1price.'&pty='.$payType.'&sid='.$siteid.'&wid='.$wx_uid.'&pn='.$payName.'&t='.$timeStamp.'&s='.$sign.'&osn='.$order_sn;
    if($u2price != 0) $param = $param . '&u2p='.$u2price;
    if($isyue != 0) $param = $param . '&iy='.$isyue;
    if($touid1 != 0) $param = $param . '&tid1='.$touid1;
    if($toutype1 != 0) $param = $param . '&tty1='.$toutype1;
    if($touid2 != 0) $param = $param . '&tid2='.$touid2;
    if($toutype2 != 0) $param = $param . '&tty2='.$toutype2;
    if($thorough != 0) $param = $param . '&thorough='.$thorough;
    switch ($pay)
    {
        case 1: //微信JS支付
            $code = "http://pay.bccoo.cn/payway/wxorderpay/wxjspaynew.aspx?" . $param;
            break;
        case 2: //微信H5支付
            $code = "http://pay.bccoo.cn/payway/wxorderpay/wxh5paynew.aspx?" . $param;
            break;
        case 3: //微信扫码支付
            $code = "http://pay.bccoo.cn/payway/wxorderpay/wxscanpaynew.aspx?" . $param;
            break;
        case 4: //支付宝移动支付
            $code = "http://pay.bccoo.cn/payway/zfborderpay/orderpaynew.aspx?" . $param;
            break;
        case 5: //支付宝扫码支付
            $code = "http://pay.bccoo.cn/payway/orderpayzfb/zfbpaynew.aspx?" . $param;
            break;
        case 6: //余额支付
            $code = "http://pay.bccoo.cn/payway/yueorderpay/yuepay.aspx?" . $param;
            break;
        case 7: //业务线预付费支付
            // -7;"[请求已过期，请重新发起支付]";
            // -8 "[签名无效]";
            // -9 "[余额不足]";
            // -5 [创建订单失败]
            // -1 [缺少必须参数]
            // 1 成功

            $code = httpRequest('http://pay.bccoo.cn/payway/yueorderpay/prepayment.ashx',"POST", $param);//调试请求连接
            Db::name('miniapp_log')->insertGetId(['site_id' => $siteid, 'wuser_id' => 1, 'log_text' =>'接口参数:'.$param.',\r\n返回值:'.$code, 'add_time' => time()]);
            break;
        case 8:
            $param = '"id":'.$id.',"uid":'.$uid.',"uty":'.$uType.',"p":'.$money.',"u1p":'.$u1price.',"u2p":'.$u2price.',"pty":'.$payType.',"sid":'.$siteid.',"iy":'.$isyue.',"wid":'.$wx_uid.',"pn":"'.$payName.'","t":"'.$timeStamp.'","s":"'.$sign.'","osn":"'.$order_sn.'"';
            if($touid1 != 0) $param = $param . ',"tid1":'.$touid1;
            if($toutype1 != 0) $param = $param . ',"tty1":'.$toutype1;
            if($touid2 != 0) $param = $param . ',"tid2":'.$touid2;
            if($toutype2 != 0) $param = $param . ',"tty2":'.$toutype2;
            $code = $param;
            break;
        case 9: //余额支付
            $pwd=md5($money.$id.$uid.$timeStamp."paywaypwd2019");
            $url = "http://pay.bccoo.cn/payway/yueorderpay/yuepaysendnew.ashx?" . $param;
            $code = httpRequest($url,"POST", 'pwd='.$pwd);//调试请求连接
            break;
    }
    return $code;
}

//微信扫码支付
function GetwechatPay($id,$uid,$uType,$touid1,$toutype1,$touid2,$toutype2,$money,$u1price,$u2price,$payType,$siteid,$isyue,$wx_uid,$payName,$thorough=0){
    $code = "";//1001 不支持微信支付 1002不支持支付宝 1003不支持财付通 1004不支持网银在线
    $timeStamp=time();
    $sign = md5($money.$id.$uid.$timeStamp."paywayccoocity2016");
    $param = 'id='.$id.'&uid='.$uid.'&uty='.$uType.'&p='.$money.'&u1p='.$u1price.'&pty='.$payType.'&sid='.$siteid.'&wid='.$wx_uid.'&pn='.$payName.'&t='.$timeStamp.'&s='.$sign;
    if($u2price != 0) $param = $param . '&u2p='.$u2price;
    if($isyue != 0) $param = $param . '&iy='.$isyue;
    if($touid1 != 0) $param = $param . '&tid1='.$touid1;
    if($toutype1 != 0) $param = $param . '&tty1='.$toutype1;
    if($touid2 != 0) $param = $param . '&tid2='.$touid2;
    if($toutype2 != 0) $param = $param . '&tty2='.$toutype2;
    if($thorough != 0) $param = $param . '&thorough='.$thorough;
    $code = "http://payjkm.bccoo.cn/payway/wxorderpay/wxscanpaynew.aspx?" . $param;
    return $code;
}
//提现
/**
 * @param $openid @微信openid
 * @param $infoID @订单id
 * @param $amount @金额 精确到分
 * @param $desc @付款描述
 * @param $paytype @业务类型
 * @param $siteid @站点id
 * @param $user_id @会员id
 */
function cash_out($openid,$infoID,$amount,$desc,$paytype,$siteid,$user_id){
    file_put_contents('./xiao.txt',strtolower($openid.$amount.$desc.$paytype.$infoID."ccooPay#2018#ccooPay"));
    $sign = md5(strtolower($openid.$amount.$desc.$paytype.$infoID."ccooPay#2018#ccooPay"));
    $param = 'openid='.$openid.'&infoID='.$infoID.'&amount='.$amount .'&desc='.$desc.'&paytype='.$paytype .'&siteID='.$siteid.'&UserID='.$user_id.'&sign='.$sign;
    $code = "http://payjkm.bccoo.cn/payway/wxorderpay/enterprisePay.aspx?" . $param;
    file_put_contents('./cash_out.txt',$code);
    return httpRequest($code);
}

//接口请求
function httpRequest($url, $method="GET", $postfields = null, $headers = array(), $debug = false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    //curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
    //return array($http_code, $response,$requestinfo);
}

//用户提现支付类型枚举
function payWay($key){
    $array=[0=>'--',1=>'微信支付',2=>'支付宝',3=>'余额',4=>'预付费'];
    return $array[$key];
}

//站点配置类型
function site_level_type(){
    return [
        1=>'居家宝',
        2=>'置业宝',
        3=>'生活宝',
    ];
}
