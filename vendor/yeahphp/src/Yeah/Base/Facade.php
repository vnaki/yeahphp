<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Base;

use Yeah\Container\Exception as ContainerException;

/**
 * 门面类
 */
abstract class Facade
{
    /**
     * 返回对象
     * @return mixed
     * @throws ContainerException
     */
    protected static function getObject()
    {
        $name = static::getAccessor();
        return Helper::app()->get($name);
    }

    /**
     * 返回门面标识
     * @return string
     */
    protected static function getAccessor()
    {
        return "";
    }

    /**
     * 静态方式调用方法
     * @param string $name 方法名称
     * @param array  $args 依赖参数
     * @return mixed
     * @throws ContainerException
     */
    public static function __callStatic($name, array $args = [])
    {
        return Helper::app()->invokeMethod([self::getObject(), $name], $args);
    }
}