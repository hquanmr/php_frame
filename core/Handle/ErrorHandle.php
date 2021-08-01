<?php

declare(strict_types=1);
namespace core\Handle;


use core\Exceptions\CoreHttpException;

/**
 * 错误处理机制
 *
 * @author TIERGB <https://github.com/TIGERB>
 */
class ErrorHandle 
{
    /**
     * 运行模式
     *
     * fpm/swoole
     * 
     * @var string
     */
    private $mode = 'fmp';

    /**
     * app instance
     *
     * @var Framework\App
     */
    private $app = null;

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
     * 脚本结束
     *
     * @return　mixed
     */
    public static function shutdown()
    { 
      
        $error = error_get_last();
        if (empty($error)) {
            return;
        }
        $this->info = [
            'type'    => $error['type'],
            'message' => $error['message'],
            'file'    => $error['file'],
            'line'    => $error['line'],
        ];

        CoreHttpException::reponseErr($this->info);
    }

    /**
     * 错误捕获
     *
     * @param  int    $errorNumber  错误码
     * @param  int    $errorMessage 错误信息
     * @param  string $errorFile    错误文件
     * @param  string $errorLine    错误行
     * @param  string $errorContext 错误文本
     * @return mixed               　
     */
    public static function errorHandler(
        $errorNumber,
        $errorMessage,
        $errorFile,
        $errorLine,
        $errorContext)
    {
   
        $this->info = [
            'type'    => $errorNumber,
            'message' => $errorMessage,
            'file'    => $errorFile,
            'line'    => $errorLine,
            'context' => $errorContext,
        ];
       
        CoreHttpException::reponseErr($this->info);
    }


}
