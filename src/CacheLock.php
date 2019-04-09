<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/9
 * Time: 10:11
 */

namespace Hamdon\Beaver;


use Carbon\Carbon;

class CacheLock
{
    static $obj = null;
    static $cacheDriver = 'redis';

    public static function create($cacheDriver = 'redis')
    {
        if (self::$obj == null || $cacheDriver != self::$cacheDriver) {
            self::$obj = new CacheLockService($cacheDriver);
        }
        self::$cacheDriver = $cacheDriver;
        return self::$obj;
    }

    /**
     *  设置缓存驱动,支持:apc、array、database、file、memcached、redis、
     *
     * @param $cacheDriver
     * @return $this
     */
    public static function setDriver($cacheDriver)
    {
        if (!in_array($cacheDriver, ["apc", "array", "database", "file", "memcached", "redis"])) {
            $cacheDriver = "redis";
        }
        self::$cacheDriver = $cacheDriver;
        return self::create($cacheDriver);
    }

    /**
     *  上锁
     *
     * @param string $name
     * @param float $minute
     */
    public static function lock($name = 'my_lock', $minute = 0.1)
    {
        \Cache::store(self::$cacheDriver)->put('beaver_lock_' . $name, 1, $minute);
    }

    /**
     *  检查是否存在此锁
     *
     * @param string $name
     * @return bool
     */
    public static function check($name = 'my_lock')
    {
        $value = \Cache::store(self::$cacheDriver)->get('beaver_lock_' . $name);
        if ($value) {
            return true;
        }
        return false;
    }

    /**
     *  解锁
     *
     * @param $name
     */
    public static function unLock($name = 'my_lock')
    {
        \Cache::store(self::$cacheDriver)->forget('beaver_lock_' . $name);
    }

    /**
     *  删除某个键的内容
     *
     * @param $name
     */
    public static function redisDelByName($name)
    {
        if (self::$cacheDriver == 'redis') {
            $redis = \Cache::getRedis();
            $keys = $redis->keys("*$name*");
            foreach ($keys as $key) {
                $redis->del($key);
            }
        }
    }

    /**
     *  查某个键的内容
     *
     * @param $name
     */
    public static function redisGetByKey($name)
    {
        $data = [];
        if (self::$cacheDriver == 'redis') {
            $redis = \Cache::getRedis();
            $keys = $redis->keys("*$key_name*");
            foreach ($keys as $key) {
                $data[] = $redis->get($key);
            }
        }
        return $data;
    }

    /**
     *  清空所有的内容
     *
     * @param $name
     */
    public static function redisForgetAllValue()
    {
        if (self::$cacheDriver == 'redis') {
            $redis = \Cache::getRedis();
            $keys = $redis->keys("*");
            foreach ($keys as $key) {
                $redis->del($key);
            }
        }
    }
}