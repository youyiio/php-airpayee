<?php
/* *
 * 功能：AirPayee小程序支付接口接口调试入口页面
 * 版本：1.0
 * 修改日期：2017-05-05
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

$baseDir = '../../';
require($baseDir . 'vendor/autoload.php');

use beyong\airpayee\PaySdk;

header("Content-type: text/html; charset=utf-8");

require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../config.php';


if (!empty($_POST['mch_order_id']) && trim($_POST['mch_order_id']) != "") {

    $mchOrderId = $_POST['mch_order_id'];
    $body = $_POST['body'];
    $amount = $_POST['amount'];
    $attach = $_POST['attach'];
    $payChannel = $_POST['pay_channel'];
    $returnUrl = $_POST['return_url'];
    $notifyUrl = $_POST['notify_url'];
    $openId = $_POST['open_id'];

    $PaySdk = new PaySdk($config);

    $PaySdk->litepay($mchOrderId, $body, $amount, $attach, $payChannel, $returnUrl, $notifyUrl, $openId);

    return;
}
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>小程序支付sdk样例</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.11/weui.min.css">
    <link rel="stylesheet" href="../example.css">
    <script src="https://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
</head>

<body data-weui-theme="light">

    <div class="page__hd">
        <h1 class="page__title">Airpayee SDK</h1>
        <p class="page__desc">小程序支付</p>
    </div>

    <form action="" method="post">
        <div class="weui-cells__title">表单</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">商户订单id</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="商品标题" name="mch_order_id" value="<?php echo time(); ?>">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">商品</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="商品标题" name="body" value="测试商品">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">总价格(分)</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" placeholder="总价格" name="amount" value="1">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">id</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="需要auth授权获取open_id, 下单时要求微信openid/支付宝buyer_id" name="open_id" value="">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">回跳地址</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="" name="return_url" value="<?php echo $config['return_url']; ?>">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">通知地址</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="" name="notify_url" value="<?php echo $config['notify_url']; ?>">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">附加</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="" name="attach" value="backattack">
                </div>
            </div>
        </div>

        <div class="weui-cells__title">商户信息</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">商户编号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="商品编号" name="mch_no" value="<?php echo $config['mch_no']; ?>">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">密钥</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="" name="secret_key" value="签名用，只能放后台" disabled>
                </div>
            </div>
        </div>

        <div class="weui-btn-area">
            <input type="hidden" name="pay_channel" value="0">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnWxpay">微信支付</a>
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnAlipay">支付宝支付</a>
        </div>
    </form>

    <script type="text/javascript">
        //表单提交操作
        $('#btnWxpay').click(function(e) {
            $("input[name=pay_channel]").val(1);
            $("form").submit();
            //        var form = $('form');
            //        var input = form.find('input').serialize();
            //        console.log(input);
            //
            //        input += "&pay_channel=1";
            //        input += '&request_time=' + encodeURIComponent("2017-07-20 20:00:00");
            //        console.log(input);
            //        window.location.href = "{:url(request()->module() . '/wap/index')}" + "?" + input;
        });
        $("#btnAlipay").click(function() {
            $("input[name=pay_channel]").val(2);
            $("form").submit();
        });
    </script>
</body>

</html>