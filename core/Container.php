<?php

declare(strict_types=1);

namespace core;

use  core\Orm\Db;
use  core\Routing\Router;
use  core\HttpKernel\RouterListener;
use  core\HttpKernel\EmitterDecorator;
use  core\HttpKernel\HttpKernelService;
use  core\HttpKernel\ControllerResolver;

use  Symfony\Component\DependencyInjection\Reference;
use  Symfony\Component\EventDispatcher\EventDispatcher;
use  Symfony\Component\DependencyInjection\ContainerBuilder;

use Laminas\Stratigility\MiddlewarePipe;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;

class Container extends ContainerBuilder
{
    protected $app;
    public  function __construct($app)
    {
        parent::__construct();
        $this->app = $app;
        $this->registerService();
    }

    public function registerService()
    {

        $this->register('ControllerResolver', ControllerResolver::class);

        $this->register('SapiEmitter', SapiEmitter::class);

        $this->register('SapiStreamEmitter', SapiStreamEmitter::class);

        $this->register('MiddlewarePipe', MiddlewarePipe::class);
        
        $this->register('EmitterDecorator', EmitterDecorator::class)
            ->addArgument(new Reference('SapiEmitter'))
            ->addArgument(new Reference('SapiStreamEmitter'));

        $this->register('EventDispatcher', EventDispatcher::class);

        $this->register('Router', Router::class)
            ->addArgument(require_once $this->app->rootPath . '/../routes/web.php');
        
          
        $this->register('Db', Db::class)
            ->addArgument($this->app->config['db'])
            ->addArgument($this->app->log);    
         
        $this->register('RouterListener', RouterListener::class)
            ->addArgument(new Reference('Router'));
            
        $this->register('HttpKernel', HttpKernelService::class)
            ->addArgument(new Reference('EventDispatcher'))
            ->addArgument(new Reference('RouterListener'))
            ->addArgument(new Reference('EmitterDecorator'))
            ->addArgument(new Reference('ControllerResolver'))
            ->addArgument(new Reference('MiddlewarePipe'));
    }
}
