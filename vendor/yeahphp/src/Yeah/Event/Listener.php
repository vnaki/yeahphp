<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Event;

use \Closure;
use Yeah\Base\Application;

/**
 * Event Listener
 */
class Listener
{
    /**
     * @var Application $app
     */
    protected $app;

    /**
     * @var array 注册的监听事件
     */
    protected $events    = [];

    /**
     * @var array 已触发的事件
     */
    protected $triggered = [];

    /**
     * @var array 已移除的事件
     */
    protected $removed   = [];

    /**
     * 构造函数
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 注册事件监听
     * @param string $name 事件名称
     * @param Closure $handler 事件处理器
     */
    public function addListener($name, Closure $handler)
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = [];
        }

        $this->events[$name][$this->getHash($handler)] = $handler;
    }

    /**
     * 移除事件监听
     * @param string $name 事件名称
     * @param Closure $handler 事件处理器
     */
    public function removeListener($name, Closure $handler = null)
    {
        if (null === $handler) {
            unset($this->events[$name]);
            return ;
        }

        if ($this->hasEvent($name)) {
            unset($this->events[$name][$this->getHash($handler)]);
        }
    }

    /**
     * 触发事件
     * @param string $name 事件名称
     * @param Event|null $event 事件对象
     * @throws \Yeah\Container\Exception
     * @throws \ReflectionException
     */
    public function trigger($name, Event $event = null)
    {
        if ($this->hasEvent($name)) {

            //默认事件对象
            if (null === $event) {
                $event = new Event();
            }

            //默认事件触发者
            if (null === $event->target) {
                $event->target = $this;
            }

            $event->name = $name;

            foreach ($this->events[$name] as $hash => $handler) {
                $this->triggered[$name][] = $handler;
                $this->app->func($handler, $event);
                //一次性事件
                if (true === $event->once) {
                    $this->removed[$name][] = $handler;
                    unset($this->events[$name][$hash]);
                }

                //停止事件
                if (true === $event->stop) {
                    break;
                }
            }
        }
    }

    /**
     * 返回事件处理器的HASH标识
     * @param Closure $handler 事件处理器
     * @return string
     */
    protected function getHash(Closure $handler)
    {
        return spl_object_hash($handler);
    }

    /**
     * 判断事件是否注册
     * @param string $name 事件名称
     * @return boolean
     */
    public function hasEvent($name)
    {
        return isset($this->events[$name]);
    }

    /**
     * 返回所有注册的事件
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * 返回已触发的事件
     * @return array
     */
    public function getTriggeredEvent()
    {
        return $this->triggered;
    }
}