<?php

namespace core\Cache;
use Psr\SimpleCache\CacheInterface;
 
class Cache implements CacheInterface{
   protected $driver; //驱动
   protected $config; //配置
   public function __construct(array $config)
   {
    
   }

   public function createDiver()
   {
       $this->driver = 
   }
   public function get($key, $default = null);
   public function set($key, $value, $ttl = null);
   public function delete($key);
   public function clear();
   public function getMultiple($keys, $default = null);
   public function setMultiple($values, $ttl = null);
   public function deleteMultiple($keys);
   public function has($key);

}