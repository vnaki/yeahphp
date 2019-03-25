<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Base;

use \Closure;
use Yeah\Ioc\Container;
use Yeah\Ioc\ServiceProvider;

abstract class Application extends Container
{
    /**
     * @var string 框架版本
     */
    const VERSION               = "1.0.0";

    /**
     * @var string APP准备事件
     */
    const EVENT_READY           = "APP_READY";

    /**
     * @var string APP引导程序事件
     */
    const EVENT_BOOT            = "APP_BOOT";

    /**
     * @var static 应用实例
     */
    protected static $instance  = null;

    /**
     * @var string 默认时区
     */
    protected $timezone         = "Asia/Shanghai";

    /**
     * @var string 框架运行状态, 可选 test and release
     */
    protected $status           = "release";

    /**
     * @var string 注册的路径空间名称
     */
    protected $pathName         = "paths";

    /**
     * @var string 默认配置读取器
     */
    protected $configReader     = "Yeah\\Factory\\Reader\\ArrayReader";

    /**
     * 构造函数
     * @param string $path 根路径
     */
    public function __construct($path)
    {
        $this[__CLASS__] = static::$instance = $this;
    }
}