<?php

declare(strict_types=1);

namespace core\HttpKernel;

use Psr\Http\Message\ServerRequestInterface;

class ControllerResolver
{
    /**
     * {@inheritdoc}
     */
    public function getController(ServerRequestInterface $request)
    {
        $route = $request->getAttribute('_route');

        if (null === $route) { //如果没有route则直接中断
            throw new \RuntimeException(sprintf('Cannot find route'));
        }
        $action = $route->getAction();
        if ($action instanceof \Closure) { // 如果是可调用的结构直接返回
            return $action;
        }
        return $this->createController($action,$request);
    }

    /**
     * 创建控制器
     *
     * @param string|array $controller
     * @return array
     */
    protected function createController($controller,$request)
    {
        list($class, $method) = is_string($controller) ? explode('::', $controller) : $controller;

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }
        return [$this->instantiateController($class,$request), $method];
    }

    /**
     * 创建控制器实例
     *
     * @param string $class A class name
     *
     * @return object
     */
    protected function instantiateController($class,$request)
    {  
        
        return new $class($request);
    }
}
