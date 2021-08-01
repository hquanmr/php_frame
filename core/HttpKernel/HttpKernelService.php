<?php

declare(strict_types=1);

namespace  core\HttpKernel;

use core\HttpKernel\RouterListener;
use core\HttpKernel\ControllerResolver;
use core\HttpKernel\Event\GetResponseEvent;
use core\Handle\CallableRequestHandler;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\Stratigility\MiddlewarePipe;
final class HttpKernelService implements RequestHandlerInterface
{
    public $eventDispatcher;
    public $emitter;
    public $middlewarePipe;
    public $controllerResolver;
    /**
     * 构造函数
     * @param EventDispatcher $dispatcher
     * @param RouterListener $RouterListener
     * @param EmitterInterface $emitter
     * @param ControllerResolver $controllerResolver
     */
    public function __construct(
        EventDispatcher $dispatcher,
        RouterListener $RouterListener,
        EmitterInterface $emitter,
        ControllerResolver $controllerResolver,
        MiddlewarePipe  $middlewarePipe
    ) {
        $this->eventDispatcher = $dispatcher;
        $this->emitter = $emitter;
        $this->middlewarePipe = $middlewarePipe;
        $this->controllerResolver = $controllerResolver;
        $dispatcher->addSubscriber($RouterListener);
    }
    /**
     * 处理请求信息
     * @param ServerRequestInterface $request
     * @return ResponseInterface $response
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
      
        $event = new GetResponseEvent($this, $request); // 1.生成事件
        $this->eventDispatcher->dispatch($event, KernelEvents::REQUEST); // 触发事件，添加路由到请求对象
        $request = $event->getRequest();  //取得自己封装过的请求 里面携带路由
      
        return  $this->middlewarePipe->process($event->getRequest(),new CallableRequestHandler([$this, 'handleRequest']));
       
    }
    /**
     * 处理请求
     * @param ServerRequestInterface $request
     * @return ResponseInterface $response
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $controller = $this->controllerResolver->getController($request); // 2. 获取闭包/控制器
        $response = call_user_func($controller); // 3. 获取响应体
        return $response;
    }

    /**
     * Emit response.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->emitter->emit($response);
    }
}
