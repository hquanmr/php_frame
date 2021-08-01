<?php

declare(strict_types=1);

namespace core\HttpKernel;


use core\Routing\Route;
use core\Routing\Router;
use core\Exceptions\CoreHttpException;
use core\HttpKernel\Event\GetResponseEvent;
use FastRoute\Dispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RouterListener   implements EventSubscriberInterface
{
    /**
     * @var Router
     */
    protected $router;
    /**
     * 构造函数
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    /**
     * 当前订阅事件
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequest'
        ];
    }
    /**
     * onRequest 请求触发
     *
     * @param GetResponseEvent $event
     * @return  void
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $matches = $this->router->dispatch($request);

        switch ($matches[0]) {
            case Dispatcher::FOUND:

                $route = $this->router->searchRoute($matches[1]);
                $this->prepareRoute($route, $matches);
                $request = $request->withAttribute('_route', $route);  // 记录当前路由到指定请求体里
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $message = sprintf(
                    'No route found for "%s %s": Method Not Allowed (Allow: %s)',
                    $request->getMethod(),
                    $request->getUri()->getPath(),
                    implode(',', $matches[1])
                );
                throw new CoreHttpException(405, $message);

            case Dispatcher::NOT_FOUND:
                $message = sprintf('No route found for "%s %s"', $request->getMethod(), $request->getUri()->getPath());
                throw new CoreHttpException(404, $message);
        }
        $event->setRequest($request);
    }

    /**
     * 预处理 route
     *
     * @param Route $route
     * @param array $matches
     */
    protected function prepareRoute(Route $route, $matches)
    {
        $routeArguments = [];
        foreach ($matches[2] as $k => $v) {
            $routeArguments[$k] = urldecode($v);
        }
        $route->setArguments($routeArguments);
    }
}
