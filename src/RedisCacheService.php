<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2023/3/30
 * Time: 18:28
 */

namespace Hamdon\Beaver;


class RedisCacheService
{
    static $obj = null;
    private $pre = 'system_cache_';

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new RedisCacheService();
        }
        return self::$obj;
    }

    /**
     * 获取缓存信息
     *
     * @param $name
     * @param null $tag
     * @return mixed
     */
    public function get($name, $tag = null)
    {
        if ($tag) {
            return \Cache::store('redis')->tags($tag)->get($name);
        } else {
            return \Cache::store('redis')->get($this->pre . $name);
        }
    }

    /**
     * 设置缓存信息
     *
     * @param $name
     * @param $data
     * @param int $second
     * @param null $tag
     */
    public function put($name, $data, $second = 0, $tag = null)
    {
        if ($tag) {
            if($second){
                \Cache::store('redis')->tags($tag)->put($name, $data, $second);
            }else{
                \Cache::store('redis')->tags($tag)->put($name, $data);
            }
        } else {
            if($second){
                \Cache::store('redis')->put($this->pre . $name, $data,$second);
            }else{
                \Cache::store('redis')->put($this->pre . $name, $data);
            }
        }
    }

    /**
     * 清除缓存信息
     *
     * @param $name
     * @param null $tag
     * @return bool
     */
    public function flush($name, $tag = null)
    {
        if ($tag) {
            if ($name) {
                \Cache::store('redis')->tags($tag)->flush($name);
            } else {
                \Cache::store('redis')->tags($tag)->flush();
            }
            return true;
        }
        $name && \Cache::store('redis')->flush($this->pre . $name);
    }

    /**
     * 回调处理缓存数组
     *
     * @param $cacheName
     * @param $param
     * @param $data
     * @param $func
     * @param int $second
     */
    public function common($cacheName, $param, &$data, $func, $second = 60)
    {
        if (!$data = self::get($cacheName)) {
            call_user_func_array($func, [$param, &$data]);
            self::put($cacheName, $data, $second);
        }
    }

    /**
     * 回调处理缓存数组-带tag
     *
     * @param $tag
     * @param $cacheName
     * @param $param
     * @param $data
     * @param $func
     * @param int $second
     */
    public function commonTag($tag, $cacheName, $param, &$data, $func, $second = 60)
    {
        if (!$data = self::get($cacheName, $tag)) {
            call_user_func_array($func, [$param, &$data]);
            self::put($cacheName, $data, $second, $tag);
        }
    }
}