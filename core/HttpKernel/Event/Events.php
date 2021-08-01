<?php
declare(strict_types=1);
namespace core\HttpKernel\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Psr\Http\Message\ServerRequestInterface;
use core\HttpKernel\HttpKernelService;


class Events extends Event
{
    public $kernel;

    public $request;

    public function __construct(HttpKernelService $kernel, ServerRequestInterface $request)
    {

        $this->kernel = $kernel;
        $this->request = $request;
    }

    /**
     * 返回当前正在处理中的请求
     *
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
