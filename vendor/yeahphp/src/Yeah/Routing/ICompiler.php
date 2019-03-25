<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Routing;

/**
 * Route Compiler Interface
 */
interface ICompiler
{
    /**
     * 编译当前路由
     * @param Route $route
     */
    public static function compile(Route $route);
}