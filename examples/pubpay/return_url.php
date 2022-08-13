<?php
/* *
 * 功能：同步通知页面
 * 版本：1.5
 * 修改日期：2020-5-05
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 */

$baseDir = '../../';
require($baseDir . 'vendor/autoload.php');

use beyong\airpayee\PaySdk;

require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../config.php';

header("Content-type: text/html; charset=utf-8");


$params = $_GET;

$PaySdk = new PaySdk($config);

$result = $PaySdk->check($params);

//验证成功
if ($result) {
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //请在这里加上商户的业务逻辑程序代码

    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取平台的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

    $is_success = $params['is_success'];
    //商户订单号
    $mch_order_id =  htmlspecialchars($params['mch_order_id']);
    //微信或支付宝的交易号，支付通道交易凭证号
    $trade_order_id = htmlspecialchars($params['trade_order_id']);
    //AirPayee交易号
    $our_order_id = htmlspecialchars($params['our_order_id']);

    if ($is_success == 'true') {
        //支付成功的业务逻辑
    } else {
        //支付失败的业务逻辑;
    }





    echo "验证成功<br /> 商户订单: $mch_order_id; AirPayee订单号：$our_order_id; 支付通道交易凭证号：$trade_order_id" ;

    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
    //验证失败
    echo "签名验证失败";
}

?>