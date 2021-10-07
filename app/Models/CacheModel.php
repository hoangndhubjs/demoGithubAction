<?php
namespace App\Models;


use Rennokki\QueryCache\Traits\QueryCacheable;

class CacheModel extends Model{

    protected $guarded = [];
//    use QueryCacheable;

//    public $cacheFor = 3600; // cache time, in seconds
//    protected static $flushCacheOnUpdate = true;
}
