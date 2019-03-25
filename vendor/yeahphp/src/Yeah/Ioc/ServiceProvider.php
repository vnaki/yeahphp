<?php
/**
 * Ioc Container Of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Ioc;

/**
 * ServiceProvider Interface
 */
interface ServiceProvider
{
    /**
     * 注册服务
     * @param Container $container
     */
    public function register(Container $container);
}