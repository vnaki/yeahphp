<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Base;

use Yeah\Container\Exception as ContainerException;
use Yeah\Routing\Router;

/**
 * 助手类
 */
class Helper
{
    /**
     * 返回`Application`实例
     * @param string $name   服务标识
     * @param array  $params 注入参数
     * @return mixed|Application
     * @throws ContainerException
     */
    public static function app($name = null, array $params = [])
    {
        if (null === $name) {
            return Application::getInstance();
        }

        return Application::getInstance()->get($name, $params);
    }

    /**
     * 返回配置
     * @param string $name 配置名称
     * @param string|null $key 键的名称
     * @return mixed
     * @throws ContainerException
     */
    public static function configGet($name, $key = null)
    {
        return static::app()->getConfig($name, $key);
    }

    /**
     * 设置配置
     * @param string $name 配置名称
     * @param mixed $value 配置值
     * @throws ContainerException
     */
    public static function configSet($name, $value)
    {
        static::app()->setConfig($name, $value);
    }

    /**
     * HTTP Request
     * @return \Yeah\Http\Request
     * @throws ContainerException
     */
    public static function request()
    {
        return static::app("http.request");
    }

    /**
     * HTTP Response
     * @param string $content
     * @param int    $status
     * @param array  $header
     * @return \Yeah\Http\Response
     * @throws ContainerException
     */
    public static function response($content, $status = 200, array $header = [])
    {
        return static::app("http.response", compact("content", "status", "header"));
    }

    /**
     * 返回路由对象
     * @return Router
     * @throws ContainerException
     */
    public static function router()
    {
        return static::app("routing.router");
    }
}