<?php
/**
 * Ioc Container Of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Ioc;

use \Closure;
use \ArrayAccess;
use \ReflectionClass;
use \ReflectionException;
use \ReflectionFunctionAbstract;

/**
 * Ioc Container
 */
class Container implements ArrayAccess, ContainerInterface
{
    /**
     * @var array 已注册的服务
     */
    protected $services  = [];

    /**
     * @var array 已注册的实例
     */
    protected $instances = [];

    /**
     * @var array 已注册的别名
     */
    protected $aliases   = [];

    /**
     * @var array 已注册的扩展
     */
    protected $extender  = [];

    /**
     * @var array 已注册的配置
     */
    protected $values    = [];

    /**
     * @var array 已注入的参数
     */
    protected $params    = [];

    /**
     * @var array 已解析的服务
     */
    protected $resolved  = [];

    /**
     * @var array 已卸载的服务
     */
    protected $removed   = [];

    /**
     * 注册服务
     * @param string $name 服务标识
     * @param string|null|Closure $value 具体服务
     * @param boolean $share 是否共享
     */
    public function set($name, $value = null, $share = false)
    {
        $name = $this->normalize($name);

        if (null === $value) {
            $value = $name;
        }

        if (!$value instanceof Closure) {
            $value = $this->getClosure($value);
        }

        $this->services[$name] = compact("value", "share");
    }

    /**
     * 注册共享服务
     * @param string $name
     * @param string|null|Closure $value
     */
    public function share($name, $value = null)
    {
        $this->set($name, $value, true);
    }

    /**
     * 注册对象服务
     * @param mixed $object
     */
    public function object($object)
    {
        if (is_object($object)) {
            $this->instances[get_class($object)] = $object;
        }
    }

    /**
     * 注册实例服务
     * @param string $name
     * @param mixed $object 具体对象
     */
    public function instance($name, $object = null)
    {
        if (is_object($object)) {
            $this->instances[$this->normalize($name)] = $object;
        }
    }

    /**
     * 注册服务的别名
     * @param string $alias
     * @param string $name
     */
    public function alias($alias, $name)
    {
        $this->aliases[$alias] = $this->normalize($name);
    }

    /**
     * 注册配置项
     * @param string $name
     * @param mixed  $value
     */
    public function value($name, $value)
    {
        $this->values[$name] = $value;
    }

    /**
     * 注册服务扩展
     * @param string  $name
     * @param Closure $extend 具体扩展
     */
    public function extend($name, Closure $extend)
    {
        $name = $this->getAlias($name);

        if (isset($this->instances[$name])) {
            $this->instances[$name] = $extend($this->instances[$name]);
        } else {
            $this->extender[$name][] = $extend;
        }
    }

    /**
     * 解析服务
     * @param string $name
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function get($name, array $params = [])
    {
        $name = $this->getAlias($name);

        if (!$this->hasSet($name)) {
            throw new Exception("Service not registered in container: " . $name);
        }

        if (!$params && !$this->resolvable($name)) {
            return $this->getResolved($name);
        }

        $this->params[] = $params;

        $object = $this->factory($this->getService($name));

        array_pop($this->params);

        foreach ($this->getExtender($name) as $extend) {
            $object = $extend($object);
        }

        return $this->resolved[$name] = $object;
    }

    /**
     * 返回别名服务标识
     * @param string $alias
     * @return string
     */
    public function getAlias($alias)
    {
        if (!isset($this->aliases[$alias])) {
            return $alias;
        }

        return $this->getAlias($this->aliases[$alias]);
    }

    /**
     * 返回服务
     * @param string $name 标识名称
     * @return mixed
     */
    protected function getService($name)
    {
        return $this->services[$name]["value"];
    }

    /**
     * 返回已解析的服务
     * @param string $name
     * @return mixed
     */
    protected function getResolved($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        return $this->resolved[$name];
    }

    /**
     * 返回配置
     * @param string $name 配置名称
     * @param string $key  选项名称
     * @return mixed
     */
    public function getValue($name, $key = null)
    {
        if (!$this->hasValue($name)) {
            return null;
        }

        $values = $this->values[$name];

        if (null === $key) {
            return $values;
        }

        return isset($values[$key]) ? $values[$key] : null;
    }

    /**
     * 返回扩展后的服务
     * @param string $name
     * @return array
     */
    protected function getExtender($name)
    {
        return $this->hasExtender($name) ? $this->extender[$name] : [];
    }

    /**
     * 返回闭包
     * @param string $class
     * @return Closure
     */
    protected function getClosure($class)
    {
        return $this->wrap(function (Container $container) use ($class) {
            return $container->instantiate($class, $container->getLastParams());
        });
    }

    /**
     * 返回当前注入的参数
     * @return array
     */
    protected function getLastParams()
    {
        return end($this->params);
    }

    /**
     * 实例化类
     * @param string $class 类名
     * @param array  $params 依赖参数
     * @return mixed
     * @throws Exception
     * @throws ReflectionException
     */
    public function instantiate($class, array $params = [])
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable())
        {
            throw new Exception($class);
        }

        $constructor = $reflector->getConstructor();

        if (null === $constructor) {
            return $reflector->newInstanceWithoutConstructor();
        }

        $params = $this->dependence($constructor, $params);

        return $reflector->newInstanceArgs($params);
    }

    /**
     * 解析参数依赖
     * @param ReflectionFunctionAbstract $reflector
     * @param array $params
     * @return array
     * @throws Exception
     */
    protected function dependence(ReflectionFunctionAbstract $reflector, array $params)
    {
        $result = [];

        foreach ($reflector->getParameters() as $dependency) {
            if (isset($params[$dependency->name])) {
                $result[] = $params[$dependency->name];
                continue;
            }

            if ($dependency->hasType()) {
                $class = $dependency->getClass()->name;

                if("Closure" != $class && $this->hasSet($class)) {
                    $result[] = $this->get($class);
                    continue;
                }
            }

            if ($dependency->isDefaultValueAvailable()) {
                $result[] = $dependency->getDefaultValue();
                continue;
            }

            throw new Exception("Lack of dependency parameter: $" . $dependency->name);
        }

        return $result;
    }

    /**
     * 调用对象或类的方法
     * @param mixed $class  类或对象
     * @param string $method 方法名
     * @param array  $params 注入参数
     * @return mixed
     * @throws Exception
     * @throws ReflectionException
     */
    public function invokeMethod($class, $method, array $params = [])
    {
        $reflector = new \ReflectionMethod($class, $method);
        return $reflector->invokeArgs(is_object($class) ? $class : null, $this->dependence($reflector, $params));
    }

    /**
     * 调用函数
     * @param string $name 函数名称
     * @param array $params 注入参数
     * @return mixed
     * @throws Exception
     * @throws ReflectionException
     */
    public function invokeFunction($name, array $params = [])
    {
        $reflector = new \ReflectionFunction($name);
        return $reflector->invokeArgs($this->dependence($reflector, $params));
    }

    /**
     * 工厂函数
     * @param Closure $closure
     * @return mixed
     */
    public function factory(Closure $closure)
    {
        return $closure($this);
    }

    /**
     * 闭包函数
     * @param Closure $closure
     * @return mixed
     */
    public function wrap(Closure $closure)
    {
        return function () use ($closure) {
            return $closure($this);
        };
    }

    /**
     * 判断是否需要解析服务
     * @param string $name 标识名称
     * @return boolean
     */
    protected function resolvable($name)
    {
        return !isset($this->instances[$name]) && (!isset($this->resolved[$name]) || !$this->isShared($name));
    }

    /**
     * 判断配置是否存在
     * @param string $name
     * @return boolean
     */
    public function hasValue($name)
    {
        return isset($this->values[$name]);
    }

    /**
     * 判断别名是否已注册
     * @param string $alias
     * @return boolean
     */
    public function hasAlias($alias)
    {
        return isset($this->aliases[$alias]);
    }

    /**
     * 判断是否已注册
     * @param string $name 标识名称
     * @return boolean
     */
    public function hasSet($name)
    {
        return isset($this->services[$name]) || isset($this->instances[$name]);
    }

    /**
     * 判断是否有扩展
     * @param string $name
     * @return boolean
     */
    public function hasExtender($name)
    {
        return isset($this->extender[$name]);
    }

    /**
     * 判断是否已共享
     * @param string $name 标识名称
     * @return boolean
     */
    public function isShared($name)
    {
        return isset($this->services[$name]) && true === $this->services[$name]["share"];
    }

    /**
     * 判断是否已解析
     * @param string $name 标识名称
     * @return boolean
     */
    public function isResolved($name)
    {
        return isset($this->resolved[$name]) || isset($this->instances[$name]);
    }

    /**
     * 移除配置
     * @param string $name 配置名称
     */
    public function removeValue($name)
    {
        if ($this->hasValue($name)) {
            unset($this->values[$name]);
        }
    }

    /**
     * 卸载服务
     * @param string $name 标识名称
     */
    public function remove($name)
    {
        if ($this->hasSet($name)) {
            $this->removed[] = $name;
            unset($this->services[$name], $this->instances[$name], $this->extender[$name]);
        }
    }

    /**
     * 重置容器
     */
    public function reset()
    {
        $this->services  = [];
        $this->resolved  = [];
        $this->removed   = [];
        $this->instances = [];
        $this->values    = [];
        $this->extender  = [];
        $this->aliases   = [];
        $this->params    = [];
    }

    /**
     * 规范标识名称
     * @param string $name
     * @return string
     */
    protected function normalize($name)
    {
        return ltrim($name, "\\");
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function offsetSet($name, $value)
    {
        if (is_object($value)) {
            $this->instance($name, $value);
        } else {
            $this->share($name, $value);
        }
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function offsetExists($name)
    {
        return $this->hasSet($name);
    }

    /**
     * @param string $name
     */
    public function offsetUnset($name)
    {
        $this->remove($name);
    }
}
