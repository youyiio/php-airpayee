<?php
                                                                                                    /* *
 * 功能：AirPayee订单操作接口接口调试入口页面
 * 版本：1.0
 * 修改日期：2022-05-05
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

header("Content-type: text/html; charset=utf-8");
$baseDir = '../../';
require($baseDir . 'vendor/autoload.php');

use beyong\airpayee\PaySdk;

require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../config.php';

//初始化支付服务类
$PaySdk = new PaySdk($config);


if (isset($_POST['method']) && !empty($_POST['method'])) {
    $method = $_POST['method'];

    switch ($method) {
        case 'airpayee.order.prepareorder':
            $mchOrderId = $_POST['mch_order_id'];
            $body = $_POST['body'];
            $amount = $_POST['amount'];
            $attach = $_POST['attach'];
            $payChannel = $_POST['pay_channel'];
            $payProduct = $_POST['pay_product'];
            $returnUrl = $_POST['return_url'];
            $notifyUrl = $_POST['notify_url'];
            $openId = $_POST['open_id'];
            try {
                $result = $PaySdk->prepareOrder($mchOrderId, $body, $amount, $attach, $payChannel, $payProduct, $returnUrl, $notifyUrl, $openId);
                echo json_encode($result);
            } catch (Exception $e) {
                echo json_encode(['is_success' => false, 'error_message' => $e->getMessage()]);
            }

            break;
        case 'airpayee.order.scanpay':
            $body = $_POST['body'];
            $amount = $_POST['amount'];
            $attach = $_POST['attach'];
            $authCode = $_POST['auth_code'];
            $result = $PaySdk->scanPay($body, $amount, $attach, $authCode);

            echo json_encode($result);
            break;
        case 'airpayee.order.query':
            $ourOrderId = $_POST['our_order_id'];
            $result = $PaySdk->query($ourOrderId);

            echo json_encode($result);
            break;
        case 'airpayee.order.refund':
            $ourOrderId = $_POST['our_order_id'];
            $amount = $_POST['amount'];
            $remark = $_POST['remark'];
            $result = $PaySdk->refund($ourOrderId, $amount, $remark);

            echo json_encode($result);
            break;
        case 'airpayee.order.cancel':
            $ourOrderId = $_POST['our_order_id'];
            $result = $PaySdk->cancel($ourOrderId);

            echo json_encode($result);
            break;
        case 'airpayee.order.*':
            break;
        default:
            break;
    }

    die();
    return;
}

?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>订单接口sdk样例</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.11/weui.min.css">
    <link rel="stylesheet" href="../example.css">
    <script src="https://cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
</head>

<body data-weui-theme="light">

    <div class="page__hd">
        <h1 class="page__title">Airpayee SDK</h1>
        <p class="page__desc">订单接口</p>
    </div>

    <form action="" method="post" id="form1">
        <div class="weui-cells__title">下单信息</div>
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
                    <label class="weui-label">总价格</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" placeholder="总价格" name="amount" value="1">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">支付渠道</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="pay_channel">
                        <option value="1">微信支付</option>
                        <option value="2">支付宝</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">open id</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="微信 h5或jssdk支付必须传" name="open_id" value="">
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
                    <input class="weui-input" type="text" placeholder="" name="attach" value="">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">支付产品</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="pay_product">
                        <option value="web">web</option>
                        <option value="h5">h5</option>
                        <option value="app">app</option>
                        <option value="jssdk">jssdk</option>
                        <option value="qrcode">qrcode</option>
                        <option value="oneqrcode">oneqrcode</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="weui-btn-area">
            <input type="hidden" name="method" value="airpayee.order.prepareorder">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnPrepareOrder">预下单</a>
        </div>
    </form>

    <form action="" method="post" id="form2">
        <div class="weui-cells__title">扫码收款</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">付款码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="支付宝或微信的付款码" name="auth_code" value="">
                </div>
            </div>
        </div>
        <div class="weui-btn-area">
            <input type="hidden" name="method" value="airpayee.order.scanpay">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnScanPay">扫码收款</a>
        </div>
    </form>

    <form action="" method="post" id="form3">
        <div class="weui-cells__title">查询订单</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">订单号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="our order id" name="our_order_id" value="">
                </div>
            </div>
        </div>
        <div class="weui-btn-area">
            <input type="hidden" name="method" value="airpayee.order.query">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnQuery">查询订单</a>
        </div>
    </form>

    <form action="" method="post" id="form4">
        <div class="weui-cells__title">退款</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">订单号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="our order id" name="our_order_id" value="">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">退款金额</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="单位分" name="amount" value="">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">备注</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="退款备注" name="remark" value="">
                </div>
            </div>
        </div>
        <div class="weui-btn-area">
            <input type="hidden" name="method" value="airpayee.order.refund">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnRefund">退款</a>
        </div>
    </form>

    <form action="" method="post" id="form5">
        <div class="weui-cells__title">撤单</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">订单号</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" placeholder="our order id" name="our_order_id" value="">
                </div>
            </div>
        </div>
        <div class="weui-btn-area">
            <input type="hidden" name="method" value="airpayee.order.cancel">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="btnCancel">撤单</a>
        </div>
    </form>

    <script type="text/javascript">
        //serialize 为JSON对象
        jQuery.prototype.serializeObject = function() {
            var a, o, h, i, e;
            a = this.serializeArray();
            o = {};
            h = o.hasOwnProperty;
            for (i = 0; i < a.length; i++) {
                e = a[i];
                if (!h.call(o, e.name)) {
                    o[e.name] = e.value;
                }
            }
            return o;
        };

        //表单提交操作
        $('#btnPrepareOrder').click(function(e) {
            var params = $('#form1').serializeArray();
            $.post('./index.php', params, function(data) {
                var result = JSON.parse(data);
                console.log(result);
                if (result.is_success) {
                    alert("操作成功");
                    $("#form3 input[name=our_order_id]").val(result.our_order_id);
                    $("#form4 input[name=our_order_id]").val(result.our_order_id);
                    $("#form5 input[name=our_order_id]").val(result.our_order_id);
                } else {
                    alert("操作失败:" + result.error_code + result.error_message);
                }

            })
        });
        $("#btnScanPay").click(function() {
            var params1 = $("#form1").serializeObject();
            var params = $("#form2").serializeObject();
            params['body'] = params1['body'];
            params['amount'] = params1['amount'];
            params['attach'] = params1['attach'];
            $.post('./index.php', params, function(data) {
                var result = JSON.parse(data);
                console.log(result);
                if (result.is_success) {
                    alert("操作成功");
                    $("#form3 input[name=our_order_id]").val(result.our_order_id);
                    $("#form4 input[name=our_order_id]").val(result.our_order_id);
                    $("#form5 input[name=our_order_id]").val(result.our_order_id);
                } else {
                    alert("操作失败:" + result.error_code + result.error_message);
                }

            })
        });

        $("#btnQuery").click(function() {
            var params = $("#form3").serializeObject();
            $.post('./index.php', params, function(data) {
                var result = JSON.parse(data);
                console.log(result);
                if (result.is_success) {
                    alert("操作成功:" + data);
                } else {
                    alert("操作失败:" + result.error_code + result.error_message);
                }

            })
        });
        $("#btnRefund").click(function() {
            var params = $("#form4").serializeObject();
            $.post('./index.php', params, function(data) {
                var result = JSON.parse(data);
                console.log(result);
                if (result.is_success) {
                    alert("操作成功:" + data);
                } else {
                    alert("操作失败:" + result.error_code + result.error_message);
                }

            })
        });

        $("#btnCancel").click(function() {
            var params = $("#form5").serializeObject();
            $.post('./index.php', params, function(data) {
                var result = JSON.parse(data);
                console.log(result);
                if (result.is_success) {
                    alert("操作成功:" + data);
                } else {
                    alert("操作失败:" + result.error_code + result.error_message);
                }

            })
        });
    </script>
</body>

</html>