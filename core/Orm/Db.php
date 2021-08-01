<?php
namespace core\Orm;
use think\DbManager ;
use Psr\Log\LoggerInterface;
class Db extends DbManager
{  
    public function __construct(array $config,LoggerInterface $log)
    { 
        
        $this->setConfig($config);
        $this->setLog($log);
        $this->modelMaker();
       
    }


}