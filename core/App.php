<?php

declare(strict_types=1);

namespace core;

use  core\Container;
use  core\Handle\ErrorHandle;
use  core\Handle\ExceptionHandle;

use think\facade\Log;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

require_once  __DIR__ . '/Helpers.php';
class App
{
    protected $config = [];  //  配置数组
    protected $rootPath  = ''; // 应用目录
    protected $log ;  // 日志记录器
    protected $container; //  内部容器
    /**
     * 构造函数
     *
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        $this->load(); //加载配置
        $this->setLog(); //设置日志对象
        $this->registerError(); //注册错误
        $this->registerException(); //注册内部异常
        
        $this->container  = new Container($this);
    }

    /**
     *  获取属性
     * @return mix
     */
    public function __get($name)
    {
        return  $this->$name;
    }
    /**
     *  设置config
     * @return void
     */
    public function load()
    {
        $this->config['log'] = require($this->rootPath . '/../config/log.php');
        $this->config['db'] = require($this->rootPath . '/../config/db.php');
    }

    /**
     *  设置log
     * @return void
     */
    public function setLog()
    {
        $logConfig = $this->config['log'];
        Log::init($logConfig);
        $this->log =   Log::record('');
    }

    /**
     * 注册错误处理机制
     * @return void
     */
    public function registerError()
    {
        error_reporting(0);
        set_error_handler([new ErrorHandle(), 'errorHandler']);
        register_shutdown_function([new ErrorHandle(), 'shutdown']);
    }

    /**
     * 注册未捕获异常函数
     * @return void
     */
    public function registerException()
    {
        set_exception_handler([new ExceptionHandle(), 'exceptionHandler']);
    }

    /**
     * 开启服务, 监听请求
     * @return void
     */
    public function serve()
    {
        $request = ServerRequestFactory::fromGlobals();
        $response = $this->handle($request);
        $this->log->save();
    }
    /**
     * 通过http内核输出
     * @return void
     */
    public function handle(ServerRequestInterface $request)
    {
        //判断cli 还是php_fpm
        $response = $this->container->get('HttpKernel')->handle($request);
        $this->container->get('HttpKernel')->terminate($request, $response); //最终输出
    }
}
