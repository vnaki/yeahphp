<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Redis;

/**
 * Redis缓存
 * @see http://www.redis.cn/topics/protocol.html#multi-bulk-reply
 */
class Redis implements IRedis
{
    /**
     * @var
     */
    protected $redis;

    /**
     * 构造函数
     */
    public function __construct()
    {
        new \Redis();
    }

    /**
     * 添加缓存
     * @param string $name
     * @param mixed  $value
     * @param int    $expire
     */
    public function set($name, $value, $expire = 0)
    {

    }

    /**
     * 读取缓存
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {

    }

    /**
     * 判断缓存是否存在
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {

    }

    /**
     * 删除缓存
     * @param string $name
     */
    public function del($name)
    {

    }

    /**
     * 增加元素值
     * @param string $name
     * @param int $value
     */
    public function inc($name, $value = 1)
    {

    }

    /**
     * 减小元素值
     * @param string $name
     * @param int $value
     */
    public function dec($name, $value = 1)
    {

    }

    /**
     * 缓存标记
     * @param string $name 标记名称
     */
    public function tag($name)
    {

    }
}