<?php

declare(strict_types=1);

namespace core\Console;

class CommandDiscover
{
    /**
     * @var string
     */
    protected $dstDir;

    /**
     * @var string
     */
    protected $namespace;

    public function __construct($namespace, $dstDir)
    {  
        $this->namespace = $namespace;
        $this->dstDir = rtrim($dstDir, '\\/');
    }

    /**
     * {@inheritdoc}
     */
    public function provide(Application $app)
    {
  
        foreach (glob("{$this->dstDir}/*Command.php") as $file) {
            
            $commands = [];

            try {
                $class = $this->namespace . pathinfo($file, PATHINFO_FILENAME);
                
                $r = new \ReflectionClass($class);
                if ($r->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') && !$r->isAbstract() && !$r->getConstructor()->getNumberOfRequiredParameters()) {
                    $command = $r->newInstance();
                    $commands[] = $command;
                }
            } catch (\ReflectionException $e) {
                // 忽略无法反射的命令
            }
         
            $app->addCommands($commands); //$this 的修改等于取地址修改
        }
    }
}
