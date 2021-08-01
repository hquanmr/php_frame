<?php

declare(strict_types=1);

namespace core\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class Controller
{

    protected $request; //注入请求对象 con
    public function __construct(ServerRequestInterface $request)
    {  
        $this->request = $request;
    }

    /**
     * @parm $data mix
     */
    public  function returnJosn($data)
    {
        return new JsonResponse($data);
    }
}
