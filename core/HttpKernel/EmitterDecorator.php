<?php

declare(strict_types=1);

namespace core\HttpKernel;

use Psr\Http\Message\ResponseInterface;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\Emitter\SapiStreamEmitter;

class EmitterDecorator implements EmitterInterface
{
    /**
     * 发送器
     */
    protected $emitter;
    /**
     * 流内存发送器
     */
    protected $streamEmitter;

    public function __construct(SapiEmitter $emitter, SapiStreamEmitter $streamEmitter)
    {
        $this->emitter = $emitter;
        $this->streamEmitter = $streamEmitter;
    }

    /**
     * {@inheritdoc}
     */
    public function emit(ResponseInterface $response): bool
    {
        if (
            !$response->hasHeader('Content-Disposition')
            && !$response->hasHeader('Content-Range')
        ) {
            return $this->emitter->emit($response);
        }
        // 内存优化型 response 输出
        return $this->streamEmitter->emit($response);
    }
}
