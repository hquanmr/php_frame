<?php
declare(strict_types=1);
namespace core\Handle;


use core\Exceptions\CoreHttpException;

/**
 * 未补货异常处理机制
 *
 * @author TIERGB <https://github.com/TIGERB>
 */
class ExceptionHandle 
{
  

    /**
     * 错误信息
     *
     * @var array
     */
    private $info = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        # code...
    }

    

    /**
     * 未捕获异常函数
     *
     * @param  object $exception 异常
     * @return void
     */
    public function exceptionHandler($exception)
    {
        $this->info = [
            'code'       => $exception->getCode(),
            'message'    => $exception->getMessage(),
            'file'       => $exception->getFile(),
            'line'       => $exception->getLine(),
            'trace'      => $exception->getTrace(),
            'previous'   => $exception->getPrevious()
        ];

        CoreHttpException::reponseErr($this->info);
    }

   
}
