# php-airpayee

**AirPayee支付的php开发库** 优先ThinkPHP5/6、Laravel的集成开发及测试。

AirPayee SDK PHP版本，包含了SDK开发库和对接案例examples；
支付渠道包含微信、支付和银联等，支持主扫、被扫、App、小程序和网页支付等支付方式。

[github地址](https://github.com/youyiio/php-airpayee)

## 特性

* 简洁的 API 设计，内部集成了支付宝，微信等众多支付通道；
* 囊括了主扫、被扫、App、小程序和网页支付等支付方式；
* 可轻松集成至 Thinkphp，Lavarel 等主流 Web 框架;


## 目录 
* [第一个AirPayee demo](#第一个AirPayee demo) 
* [安装](#安装) 
    * [使用 Composer 安装 (强烈推荐)](#使用-composer-安装-强烈推荐)
    * [github下载 或 直接手动下载源码](#github下载-或-直接手动下载源码)
        * [下载文件](#下载文件)
        * [引入自动载入文件](#引入自动载入文件)


## 第一个AirPayee demo

**ThinkPHP5/6 示例**
```
use beyong\airpayee\ECharts;
use beyong\airpayee\options\YAxis;
use beyong\airpayee\Option;
use beyong\airpayee\charts\Bar;

$echarts = ECharts::init("#myChart");

$option = new Option();
$option->title(['text' => 'ECharts 入门示例']);
$option->xAxis(["data" => ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子']]);
$option->yAxis([]);

$chart = new Bar();
$chart->data = [5, 20, 36, 10, 10, 20];
$option->addSeries($chart);

$echarts->option($option);

$content = $echarts->render();
echo $content;
```



## 安装
### 使用 Composer 安装 (强烈推荐):
支持 `psr-4` 规范, 开箱即用
```
composer require youyiio/php-airpayee
```

### github下载 或 直接手动下载源码:
需手动引入自动载入文件

#### 下载文件:
git clone https://github.com/youyiio/php-airpayee php-airpayee


#### 引入自动载入文件:
使用时引入或者全局自动引入

`require_once '/path/to/php-airpayee/src/autoload.php`;



## 示例 - Line
```
$echarts = ECharts::init("#myChart");

$option = new Option();
$option->title(['text' => 'ECharts 入门示例']);
$option->xAxis(["data" => ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子']]);
$option->yAxis([]);

$chart = new Line();
$chart["data"] = [5, 20, 36, 10, 10, 20];
$option->series([$chart]);

$echarts->option($option);

$content = $echarts->render();
echo $content;
```

## 示例 - Bar
```
$echarts = ECharts::init("#myChart");

$option = new Option();
$option->title(['text' => 'ECharts 入门示例']);
$option->xAxis(["data" => ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子']]);
$option->yAxis([]);
$option->legend(["data" => ['销量']]); //显示指定的series的标记，对应chart->name

$chart = new Bar();
$chart->name = '销量';
$chart->data = [5, 20, 36, 10, 10, 20];
$option->addSeries($chart);

$echarts->option($option);

$content = $echarts->render();
echo $content;
```



## Issues
如果有遇到问题请提交 [issues](https://github.com/youyiio/php-airpayee/issues)


## License
Apache 2.0
