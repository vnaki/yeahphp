<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Routing;

use Yeah\Base\Facade;

/**
 * 路由门面
 */
class RouterFacade extends Facade
{
    /**
     * 返回门面标识
     * @return string
     */
    protected static function getAccessor()
    {
        return "router";
    }
}