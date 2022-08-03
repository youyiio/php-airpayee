<?php
/* *
 * 功能：聚合支付， h5wap支付及Web api业务参数封装
 * 版本：1.5
 * 修改日期：2020-09-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

namespace beyong\airpayee;

require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./config.php';
require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./Http.php';

class PayService {

    const PAY_CHANNEL_WXPAY   = 1;
    const PAY_CHANNEL_ALIPAY  = 2;

    //商户编号
    public $mch_no = "";

    //商户密钥
    public $secret_key = "";

    //异步通知地址
    public $notify_url = "http://xxx.xx/notify_url.php";

    //同步跳转
    public $return_url = "http://xx.xx/paySuccess";

    //编码格式
    public $charset = "UTF-8";

    //签名方式,固定值
    public $sign_type = "MD5";

    //统一的网关接口，定值无需修改
	public $unify_gateway_url = "https://pay.ituizhan.com/gateway/unify";

    //扫码枪支付method, 固定值
    public static $scanpay_method = "airpayee.pay.scanpay";

    //app支付method, 固定值
    public static $apppay_method = "airpayee.pay.apppay";

    //web支付method，固定值
    public static $webpay_method = "airpayee.pay.methodpay";

    //公众号/生活号支付method，固定值
    public static $pubpay_method = "airpayee.pay.pubpay";

    //小程序支付method，固定值
    public static $litepay_method = "airpayee.pay.litepay";

    //H5支付method，固定值
    public static $h5pay_method = "airpayee.pay.h5pay";

    //预下单method，固定值
    public static $prepayorder_method = "airpayee.order.prepareorder";
    //查询订单method，固定值
    public static $queryorder_method = "airpayee.order.query";
    //取消订单method，固定值
    public static $cancelorder_method = "airpayee.order.refund";
    //退款订单method，固定值
    public static $refundorder_method = "airpayee.order.cancel";


    //http请求类
    private $http;

    function __construct($merge_config) {
        $this->mch_no = $merge_config['mch_no'];
        $this->secret_key = $merge_config['secret_key'];
        $this->return_url = $merge_config['return_url'];
        $this->notify_url = $merge_config['notify_url'];
        $this->charset = $merge_config['charset'];
        $this->unify_gateway_url = $merge_config['unify_gateway_url'];
        if (empty($this->mch_no) || trim($this->mch_no)=="") {
            throw new \Exception("mch_no should not be NULL!");
        }
        if (empty($this->secret_key) || trim($this->secret_key) == "") {
            throw new \Exception("secret_key should not be NULL!");
        }
        if (empty($this->charset) || trim($this->charset) == "") {
            throw new \Exception("charset should not be NULL!");
        }
        if (empty($this->unify_gateway_url) || trim($this->unify_gateway_url) == "") {
            throw new \Exception("unify_gateway_url should not be NULL!");
        }

        $this->http = new \QL\Ext\Lib\Http();
    }

    /**
     * 扫码枪收款
     *
     * @param [type] $body
     * @param [type] $amount
     * @param [type] $attach
     * @param [type] $authCode
     * @return void
     */
    public function scanPay($body, $amount, $attach, $authCode)
    {
        $params = [
            'method' => PayService::$scanpay_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'auth_code' => $authCode,
            'body' => $body,
            'amount' => $amount,
            'attach' => $attach,
        ];

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);
        //var_dump($result);

        return $result;
    }

    /**
     * 公众号/生活号支付，当前支持微信、支付宝内部浏览器；
     * @param $mchOrderId 商户订单号
     * @param $body 商品描述
     * @param $amount 总费用，单位分
     * @param $attach 附加信息，回调或异步通知时原格式回传
     * @param $payChannel 支付渠道
     * @param $returnUrl 回调同步通知地址，公网可访问
     * @param $notifyUrl 异步通知地址，公网可访问
     * @param string $openId  微信或支付宝平台用户id
     * @throws Exception
     * @return bool
     */
    function pubPay($mchOrderId, $body, $amount, $attach, $payChannel, $returnUrl, $notifyUrl, $openId = '')
    {
        if ($payChannel == PayService::PAY_CHANNEL_WXPAY && empty($openId)) {
            throw new \Exception('pay channel, open id参数错误');
        }
        if (is_int($amount)) {
            $amount = intval($amount);
        }
        $params = [
            'method' => PayService::$pubpay_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'mch_order_id' => $mchOrderId,
            'pay_channel' => $payChannel,
            'body' => $body,
            'amount' => $amount,
            'attach' => $attach,
            'return_url' => $returnUrl,
            'notify_url' => $notifyUrl,
        ];
        if (!empty($openId)) {
            $bizParams['open_id'] = $openId;
        }

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $redirect_url = $this->unify_gateway_url . '?' . http_build_query($params);

        header('Location: ' . $redirect_url);
    }

    /**
     * 网页web支付，当前支持PC浏览器，移动浏览器；
     * @param $mchOrderId 商户订单号
     * @param $body 商品描述
     * @param $amount 总费用，单位分
     * @param $attach 附加信息，回调或异步通知时原格式回传
     * @param $payChannel 支付渠道
     * @param $returnUrl 回调同步通知地址，公网可访问
     * @param $notifyUrl 异步通知地址，公网可访问
     * @throws Exception
     * @return bool
     */
    function webPay($mchOrderId, $body, $amount, $attach, $payChannel, $returnUrl, $notifyUrl)
    {
        if (is_int($amount)) {
            $fee = intval($amount);
        }
        $params = [
            'method' => PayService::$webpay_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'mch_order_id' => $mchOrderId,
            'pay_channel' => $payChannel,
            'pay_product' => 'web',
            'body' => $body,
            'amount' => $fee,
            'attach' => $attach,
            'return_url' => $returnUrl,
            'notify_url' => $notifyUrl,
        ];

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $redirect_url = $this->unify_gateway_url . '?' . http_build_query($params);

        header('Location: ' . $redirect_url);
    }

    /**
     * App支付
     *
     * @param $mchOrderId
     * @param $body
     * @param $amount
     * @param $attach
     * @param $payChannel
     * @param $notifyUrl
     * @return array
     */
    public function apppay($mchOrderId, $body, $amount, $attach , $payChannel, $notifyUrl)
    {
        //1:微信，2:支付宝
        if (!in_array($payChannel, [PayService::PAY_CHANNEL_WXPAY, PayService::PAY_CHANNEL_ALIPAY])) {
            throw new \Exception('pay channel参数错误');
        }

        $params = [
            'method' => PayService::$apppay_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'mch_order_id' => $mchOrderId,
            'body' => $body,
            'amount' => $amount,
            'attach' => $attach,
            'pay_channel' => $payChannel,
            'pay_product' => 'app',
            'notify_url' => $notifyUrl,
        ];
        if (!empty($openId)) {
            $bizParams['open_id'] = $openId;
        }

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * 小程序支付
     *
     * @param $mchOrderId
     * @param $body
     * @param $amount
     * @param $attach
     * @param $payChannel
     * @param $notifyUrl
     * @param string $openId
     * @return array
     */
    public function litepay($mchOrderId, $body, $amount, $attach , $payChannel, $notifyUrl, $openId = '')
    {
        //1:微信，2:支付宝
        if (!in_array($payChannel, [PayService::PAY_CHANNEL_WXPAY, PayService::PAY_CHANNEL_ALIPAY])) {
            throw new \Exception('pay channel参数错误');
        }

        if ($payChannel == PayService::PAY_CHANNEL_WXPAY && empty($openId)) {
            throw new \Exception('pay channel, open id参数错误');
        }

        $params = [
            'method' => PayService::$litepay_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'mch_order_id' => $mchOrderId,
            'body' => $body,
            'amount' => $amount,
            'attach' => $attach,
            'pay_channel' => $payChannel,
            'pay_product' => 'lite',
            'notify_url' => $notifyUrl,
        ];
        if (!empty($openId)) {
            $bizParams['open_id'] = $openId;
        }

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * h5支付
     *
     * @param $mchOrderId
     * @param $body
     * @param $amount
     * @param $attach
     * @param $payChannel
     * @param $notifyUrl
     * @param string $openId
     * @return array
     */
    public function h5pay($mchOrderId, $body, $amount, $attach , $payChannel, $notifyUrl)
    {
        //1:微信，2:支付宝
        if (!in_array($payChannel, [PayService::PAY_CHANNEL_WXPAY, PayService::PAY_CHANNEL_ALIPAY])) {
            throw new \Exception('pay channel参数错误');
        }

        $params = [
            'method' => PayService::$prepayorder_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'mch_order_id' => $mchOrderId,
            'body' => $body,
            'amount' => $amount,
            'attach' => $attach,
            'pay_channel' => $payChannel,
            'pay_product' => 'h5',
            'notify_url' => $notifyUrl,
        ];

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * 预下单接口
     *
     * @param $mchOrderId
     * @param $body
     * @param $amount
     * @param $attach
     * @param $payChannel
     * @param $payProduct
     * @param $returnUrl
     * @param $notifyUrl
     * @param string $openId
     * @return array
     */
    public function prepareOrder($mchOrderId, $body, $amount, $attach , $payChannel, $payProduct, $returnUrl, $notifyUrl, $openId = '')
    {
        //1:微信，2:支付宝
        if (!in_array($payChannel, [PayService::PAY_CHANNEL_WXPAY, PayService::PAY_CHANNEL_ALIPAY])) {
            throw new \Exception('pay channel参数错误');
        }
        if (!in_array($payProduct, ['web', 'h5', 'app', 'pub', 'lite', 'scan'])) {
            throw new \Exception('pay product参数错误');
        }

        if ($payChannel == PayService::PAY_CHANNEL_WXPAY && $payProduct == 'h5' && empty($openId)) {
            throw new \Exception('pay channel, open id参数错误');
        }

        $params = [
            'method' => PayService::$prepayorder_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'mch_order_id' => $mchOrderId,
            'body' => $body,
            'amount' => $amount,
            'attach' => $attach,
            'pay_channel' => $payChannel,
            'pay_product' => $payProduct,
            'return_url' => $returnUrl,
            'notify_url' => $notifyUrl,
        ];
        if (!empty($openId)) {
            $bizParams['open_id'] = $openId;
        }

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);

        return $result;
    }

    /**
     * 查询订单
     *
     * @param [type] $ourOrderId
     * @return array
     */
    public function query($ourOrderId)
    {
        $params = [
            'method' => PayService::$queryorder_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'our_order_id' => $ourOrderId,
        ];

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);
        //var_dump($result);

        return $result;
    }

    /**
     * 订单退款
     *
     * @param [type] $ourOrderId
     * @param [type] $amout
     * @param [type] $remark
     * @return array
     */
    function refund($ourOrderId, $amount, $remark = null)
    {
        $params = [
            'method' => PayService::$refundorder_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'our_order_id' => $ourOrderId,
            'amount' => $amount,
        ];
        if (!empty($remark)) {
            $bizParams['remark'] = $remark;
        }

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->unify_gateway_url, $params);
        $result = json_decode($response, true);
        //var_dump($result);

        return $result;
    }

    /**
     * 取消订单
     *
     * @param string $ourOrderId
     * @return array
     */
    function cancel($ourOrderId)
    {
        $params = [
            'method' => PayService::$cancelorder_method,
            'mch_no' => $this->mch_no,
            'request_time' => date('Y-m-d H:i:s', time()),
            'sign' => '',
        ];

        $bizParams = [
            'our_order_id' => $ourOrderId,
        ];

        $params = array_merge($params, $bizParams);
        $sign = $this->sign_params($params, $this->secret_key);
        $params['sign'] = $sign;

        $response = $this->http->post($this->web_api_url, $params);
        $result = json_decode($response, true);
        //var_dump($result);

        return $result;
    }


        /**
     * 验签方法
     * @param $params 验签平台返回的信息，使用支付宝公钥。
     * @return boolean
     */
    function check($params)
    {
        if (empty($params) || !array_key_exists('sign', $params)) {
            return false;
        }

        $sign = $this->sign_params($params, $this->secret_key);
        if ($sign == $params['sign']) {
            return true;
        } else {
            return false;
        }
    }

    //请求参数签名
    /**
     * @param $params, 待签名参数
     * @param $secret_key， 参与签名的密钥
     * @return 返回签名结果
     */
    function sign_params($params, $secret_key)
    {
        if (empty($params) || empty($secret_key)) {
            return '';
        }

        ksort($params);

        $paramString = '';
        $resultSign = '';
        foreach ($params as $key => $value) {
            if ($key == 'sign') {
                continue;
            }

            $paramString = $paramString . $key . '=' . htmlspecialchars_decode($value);
            $paramString = $paramString . '&';
        }

        $paramString = substr($paramString, 0, strlen($paramString) - 1);

        $sign_type = strtoupper($this->sign_type);
        $paramString = $paramString . $secret_key;

        if ($sign_type == 'md5') {
            $resultSign = md5($paramString);
        } else {
            $resultSign = md5($paramString);
        }


        return $resultSign;
    }
}

?>