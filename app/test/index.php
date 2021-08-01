<?php

namespace app\test;

use  core\Controller\Controller;
use  app\model\test;

class index extends Controller
{
    public function index()
    {    
        $a  = new test();
        p($a->find());exit();
        app('Db')->table('sss');exit();
       return  $this->returnJosn('ss');
    }
}
