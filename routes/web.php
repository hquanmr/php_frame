<?php

use core\Routing\RouteCollector;
use Laminas\Diactoros\Response\TextResponse;
use app\middleware\TestMiddleware;

$pipe = $this->get('MiddlewarePipe'); //在容器类的上下文里面是可以拿到$this的

$RouteCollector = new  RouteCollector();
//添加中间件
$RouteCollector->pipe(function ($request,  $handler) {
    $response = $handler->handle($request);
    return $response->withHeader('X-Jade-Version', '0.0.1');
}, $pipe)->get('/', function () {
    return new TextResponse('pong');
});

$RouteCollector->pipe(TestMiddleware::class, $pipe)->get('/json', 'app\test\index::index');


return $RouteCollector->getRoutes();
