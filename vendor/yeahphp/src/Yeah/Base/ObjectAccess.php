<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Base;

/**
 * 对象访问
 */
class ObjectAccess implements \ArrayAccess
{
    /**
     * @var array 不可见的属性
     */
    protected $items = [];

    /**
     * 设置不可访问属性
     * @param string $name
     * @param mixed  $value
     *
     * ```
     * $obj->name = 'test'
     * ```
     */
    public function __set($name, $value)
    {
        $this->items[$name] = $value;
    }

    /**
     * 读取不可见属性
     * @param string $name
     * @return mixed
     *
     * ```
     * $object->name
     * ```
     */
    public function __get($name)
    {
        if(isset($this->items[$name]))
        {
            return $this->items[$name];
        }

        return null;
    }

    /**
     * 序列化对象
     * @return array
     */
    public function __sleep()
    {
        return $this->items;
    }

    /**
     * 反序列化之前操作
     */
    public function __wakeup()
    {

    }

    /**
     * isset不可见属性
     * @param string $name
     * @return boolean
     *
     * ```
     * isset($obj->abc)
     * ```
     */
    public function __isset($name)
    {
        return isset($this->items[$name]);
    }

    /**
     * unset不可见属性
     * @param string $name
     *
     * ```
     * unset($obj->abc)
     * ```
     */
    public function __unset($name)
    {
        if (isset($this->items[$name])) {
            unset($this->items[$name]);
        }
    }

    /**
     * 以函数形式访问对象
     * @param string $name
     * @return mixed
     *
     * ```
     * $object($name)
     * ```
     */
    public function __invoke($name)
    {
        return $this->__get($name);
    }

    /**
     * var_export将结果以类形式导出
     * @param array $properties
     * @return \stdClass
     */
    public static function __set_state(array $properties)
    {
        $std = new \stdClass();

        foreach ($properties as $key => $value) {
            $std->$key = $value;
        }

        return $std;
    }

    /**
     *
     */
    public function __clone()
    {

    }

    /**
     * 方法不存在
     * @param string $name
     * @param array  $args
     * @throws MethodException
     */
    public function __call($name, $args)
    {
        throw new MethodException(__CLASS__, "->", $name);
    }

    /**
     * 方法不存在
     * @param string $name
     * @param array  $args
     * @throws MethodException
     */
    public static function __callStatic($name, $args)
    {
        throw new MethodException(__CLASS__, "::", $name);
    }

    /**
     * 将对象当做字符串操作时处理
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->items, 256);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function offsetSet($name, $value)
    {
        $this->__set($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->__get($name);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function offsetExists($name)
    {
        return $this->__isset($name);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function offsetUnset($name)
    {
        return $this->__unset($name);
    }
}