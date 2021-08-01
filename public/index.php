<?php

declare(strict_types=1);
// error_reporting(0); 
// 只有当 error_reporting(0) 语法解析 ok，开始载入解释执行到此处时
// 后面的内存 才会开始被 Zend Engine 做语法解析/解释运行 才能铺货全部错误
require __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../core/App.php';

use core\Exceptions\CoreHttpException;

try {
    $app = new   \core\App(__DIR__);
    $app->serve();
} catch (CoreHttpException $e) {

    $e->reponse();
}

// 入口文件　----> 注册自加载函数
//         ----> 注册错误(和异常)处理函数
//         ----> 加载配置文件
//  前面三个可以全局变量
//         ----> 请求
//         ----> 路由　
//         ---->（控制器 <----> 数据模型）
//         ----> 响应
//         ----> json
//         ----> 视图渲染数据
//内部变量和全局变量 easy-php 就是for循环设置全局变量参数配置 然后route只执行内部变量
//框架最重要就是路由 分发到具体执行通过回调  
//$this 的修改等于取地址修改

