<?php
/**
 * Config Loader Component of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Config;

use Yeah\Base\Application;

/**
 * Config Loader
 */
class Loader
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string 配置板式类
     */
    protected $formatClass = "Yeah\\Config\\Formats\\ArrayFormat";

    /**
     * 构造函数
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 从文件读取配置
     * @param string $file 配置文件
     * @throws \Yeah\Ioc\Exception
     * @throws \ReflectionException
     * @throws \Yeah\Config\Exception
     */
    public function fromFile($file)
    {
        if (!is_file($file)) {
            throw new Exception("Config file not exist: " . $file);
        }

        $config = $this->app->invokeMethod($this->formatClass, "format", compact("file"));

        foreach ($config as $name => $value) {
            $this->app->value($name, $value);
        }
    }

    /**
     * 从目录中读取配置
     * @param string $directory 配置目录
     * @throws \Yeah\Config\Exception
     */
    public function formDirectory($directory)
    {
        if (!is_dir($directory)) {
            throw new Exception("Configuration directory does not exist: " . $directory);
        }

        glob();
    }

}