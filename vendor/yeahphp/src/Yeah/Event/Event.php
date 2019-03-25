<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Event;

/**
 * Event Class
 */
class Event
{
    /**
     * @var string 事件名称
     */
    public $name;

    /**
     * @var mixed 事件数据
     */
    public $data;

    /**
     * @var mixed 事件触发者
     */
    public $target;

    /**
     * @var boolean 是否一次性事件
     */
    public $once = false;

    /**
     * @var boolean 是否停止事件
     */
    public $stop = false;
}