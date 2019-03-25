<?php
/**
 * Ioc Container Of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Ioc;

use \Closure;

/**
 * Ioc Container Interface
 */
interface ContainerInterface
{
    /**
     * 注册服务
     * @param string $name 服务标识
     * @param string|null|Closure $value 具体服务
     * @param boolean $share 是否共享
     */
    public function set($name, $value = null, $share = false);

    /**
     * 注册共享服务
     * @param string $name
     * @param string|null|Closure $value
     */
    public function share($name, $value = null);

    /**
     * 注册对象服务
     * @param mixed $object
     */
    public function object($object);

    /**
     * 注册实例服务
     * @param string $name
     * @param mixed $object 具体对象
     */
    public function instance($name, $object = null);

    /**
     * 注册服务的别名
     * @param string $alias
     * @param string $name
     */
    public function alias($alias, $name);

    /**
     * 注册配置项
     * @param string $name
     * @param mixed  $value
     */
    public function value($name, $value);

    /**
     * 注册服务扩展
     * @param string  $name
     * @param Closure $extend 具体扩展
     */
    public function extend($name, Closure $extend);

    /**
     * 解析服务
     * @param string $name
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function get($name, array $params = []);

    /**
     * 返回别名服务标识
     * @param string $alias
     * @return string
     */
    public function getAlias($alias);

    /**
     * 返回配置
     * @param string $name 配置名称
     * @param string $key  选项名称
     * @return mixed
     */
    public function getValue($name, $key = null);

    /**
     * 实例化类
     * @param string $class 类名
     * @param array  $params 依赖参数
     * @return mixed
     * @throws Exception
     * @throws \ReflectionException
     */
    public function instantiate($class, array $params = []);

    /**
     * 调用对象或类的方法
     * @param mixed $class  类或对象
     * @param string $method 方法名
     * @param array  $params 注入参数
     * @return mixed
     * @throws Exception
     * @throws \ReflectionException
     */
    public function invokeMethod($class, $method, array $params = []);

    /**
     * 调用函数
     * @param string $name 函数名称
     * @param array $params 注入参数
     * @return mixed
     * @throws Exception
     * @throws \ReflectionException
     */
    public function invokeFunction($name, array $params = []);

    /**
     * 工厂函数
     * @param Closure $closure
     * @return mixed
     */
    public function factory(Closure $closure);

    /**
     * 闭包函数
     * @param Closure $closure
     * @return mixed
     */
    public function wrap(Closure $closure);

    /**
     * 判断配置是否存在
     * @param string $name
     * @return boolean
     */
    public function hasValue($name);

    /**
     * 判断别名是否已注册
     * @param string $alias
     * @return boolean
     */
    public function hasAlias($alias);

    /**
     * 判断是否已注册
     * @param string $name 标识名称
     * @return boolean
     */
    public function hasSet($name);

    /**
     * 判断是否有扩展
     * @param string $name 标识名称
     * @return boolean
     */
    public function hasExtender($name);

    /**
     * 判断是否已共享
     * @param string $name 标识名称
     * @return boolean
     */
    public function isShared($name);

    /**
     * 判断是否已解析
     * @param string $name 标识名称
     * @return boolean
     */
    public function isResolved($name);

    /**
     * 移除配置
     * @param string $name 配置名称
     */
    public function removeValue($name);

    /**
     * 卸载服务
     * @param string $name 标识名称
     */
    public function remove($name);

    /**
     * 重置容器
     */
    public function reset();
}