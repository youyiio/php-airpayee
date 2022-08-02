<?php
/* *
 * 功能：壹收银移动Wap网站支付接口接口调试入口页面
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
    <title>聚合支付sdk样例</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- 引入jquery-weui -->
    <link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
    <script src="https://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>

</head>
<body>

    <div class="weui-cells__title">商户信息(注，请与商务联系，开通商户获取商户编号和密钥，交流群：)</div>
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
                <input class="weui-input" type="text" placeholder="" name="mch_key" value="签名用，只能放后台" disabled>
            </div>
        </div>
    </div>

    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="./wappay" id="">移动Wap支付(h5)</a>
        <a class="weui-btn weui-btn_primary" href="./webapi" id="">Web接口</a>
    </div>
    </form>
</body>
</html>