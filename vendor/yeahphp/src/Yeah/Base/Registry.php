<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Base;

/**
 * 注册登记
 */
abstract class Registry
{
    /**
     * @var Application $app
     */
    protected $app;

    /**
     * @var array 待注册的服务
     */
    protected $services   = [];

    /**
     * @var array 待注册的服务别名
     */
    protected $aliases    = [];

    /**
     * @var array 待注册的服务提供者
     */
    protected $providers  = [];

    /**
     * @var array 注册别名路径
     */
    protected $paths      = [];

    /**
     * 构造函数
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 框架启动加载
     */
    public function bootstrap()
    {

    }
}