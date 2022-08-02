<?php
ini_set('date.timezone','Asia/Shanghai');

$config = array (	
	//商户编号
	'mch_no' => "",

	//商户密钥
	'secret_key' => "",
	
	//异步通知地址
	'notify_url' => "http://shouyinsdk.airpayee.com/wappay/notify_url.php",
	
	//同步跳转
	'return_url' => "http://shouyinsdk.airpayee.com/wappay/return_url.php",

	//编码格式，定值无需修改
	'charset' => "UTF-8",

	//移动wap支付，定值无需修改
	'wap_pay_url' => "http://www.airpayee.com/gate/wap", //http://www.airpayee.com/gate/wap

	//web api接口地址，定值无需修改
	'web_api_url' => "http://www.airpayee.com/gate/web", //http://www.airpayee.com/gate/web
	
);