<?php
namespace core\orm;
use think\Model as TModel;
class Model extends TModel
{
    public function __construct()
    {
      $this->setDb(app('Db'));
    }
}