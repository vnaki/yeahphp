<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Exception;

/**
 * 类方法不存在
 */
class MethodException extends YeahException
{
    protected $message = "Class method does not exist: %s%s%s";
}