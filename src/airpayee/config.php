<?php
ini_set('date.timezone','Asia/Shanghai');

$config = array (	
	//商户编号
	'mch_no' => "",

	//商户密钥
	'secret_key' => "",
	
	//异步通知地址
	'notify_url' => "http://shouyinsdk.airpayee.com/webpay/notify_url.php",
	
	//同步跳转
	'return_url' => "http://shouyinsdk.airpayee.com/webpay/return_url.php",

	//编码格式，定值无需修改
	'charset' => "UTF-8",

	//统一的网关接口，定值无需修改
	'unify_gateway_url' => "https://pay.ituizhan.com/gateway/unify", //https://pay.ituizhan.com/gateway/unify
	
);