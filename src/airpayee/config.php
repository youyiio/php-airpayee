<?php
ini_set('date.timezone','Asia/Shanghai');

$config = array (	
	//商户编号
	'mch_no' => "",

	//商户密钥
	'secret_key' => "",
	
	//异步通知地址，需修改为开发者的地址
	'notify_url' => "http://paysdk.airpayee.com/webpay/notify_url.php",
	
	//同步跳转，需修改为开发者的地址
	'return_url' => "http://paysdk.airpayee.com/webpay/return_url.php",

	//编码格式，定值无需修改
	'charset' => "UTF-8",

	//统一的网关接口，定值无需修改
	'unify_gateway_url' => "https://www.airpayee.com/gateway/unify"
	
);