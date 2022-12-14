<?php
/* *
 * 功能：AirPayee支付sdk导航页面
 * 版本：1.0
 * 修改日期：2017-05-05
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

header("Content-type: text/html; charset=utf-8");

require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./config.php';

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Airpayee支付sdk样例</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.11/weui.min.css">
    <link rel="stylesheet" href="./example.css">

</head>

<body data-weui-theme="light">

    <div class="page__hd">
        <h1 class="page__title">Airpayee SDK</h1>
        <p class="page__desc">支付产品sample</p>
    </div>

    <div class="weui-cells__title">商户信息(注，请与商务联系，https://www.airpayee.com 登录平台获取)</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">商户编号</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="text"  placeholder="商品编号" name="mch_no" value="<?php echo $config['mch_no']; ?>">
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
        <a class="weui-btn weui-btn_primary" href="./scanpay" id="">扫码支付</a>
        <a class="weui-btn weui-btn_primary" href="./webpay" id="">网页支付</a>
        <a class="weui-btn weui-btn_primary" href="./pubpay" id="">公众号/生活号支付</a>
        <a class="weui-btn weui-btn_primary" href="./litepay" id="">小程序支付</a>
        <a class="weui-btn weui-btn_primary" href="./h5pay" id="">h5支付</a>
        <a class="weui-btn weui-btn_primary" href="./apppay" id="">移动应用支付</a>
        <a class="weui-btn weui-btn_primary" href="./orderapi/index.php" id="">订单接口</a>
    </div>
    </form>
</body>
</html>