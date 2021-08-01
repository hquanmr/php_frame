<?php
declare(strict_types=1);
namespace core\Console;

use core\App;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends SymfonyApplication
{
    /**
     * Logo Text
     *
     * @var string
     */
    const LOGO = <<<EOT
 ____  _          ___
/ ___|| |__   ___|_ _|_ __
\___ \| '_ \ / _ \| || '_ \
 ___) | | | |  __/| || | | |
|____/|_| |_|\___|___|_| |_|   

EOT;

    /**
     * @var App
     */
    // protected $decorated; App $app
    public   $rootPath  = ''; // 应用目录
    protected $commandDiscoverer;
    public function __construct(string $rootPath)
    {   
        $this->rootPath =$rootPath ;
        $this->commandDiscoverer =  new CommandDiscover('app\console\\', $this->rootPath.'/app/console');
        // $this->decorated = $app;
        parent::__construct('Jade', '0.0.1');
    }

    /**
     * {@inheritdoc}
     */
    public function getHelp()
    {
        return parent::getHelp() . PHP_EOL . static::LOGO;
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        // $this->decorated->boot(); 内部变量需要的时候再启动
       
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    protected function registerCommands()
    {
        $this->commandDiscoverer->provide($this);
    }
}
