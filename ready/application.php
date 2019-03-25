<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

use App\Application;
use Yeah\Base\Helper;
use Yeah\Base\HandleException;

/**
 * 应用程序的根路径
 */
$basePath = dirname(__DIR__);

/**
 * 引入类自动加载器,按照PSR-4和PSR-0规范去加载类文件
 */
require $basePath . "/ready/autoload.php";

/**
 * 监听框架的错误和异常
 */
(new HandleException())->listen();

/**
 * 创建应用对象
 */
$app = new Application($basePath);

/**
 *
 */
$app->share("Yeah\\Registry", "App\\Registry");

/**
 * 初始化基础服务注册等
 */
//$app->initialize();

/**
 * 设置要加载的配置文件目录
 */
$app->configFrom($basePath . "/config");

$app->bootstrap();

Helper::router()->group(["prefix" => "home/"], function ($router) {
    $router->get("set/:id", function () {

    })->domain("qq.com");

    $router->group(["domain" => "baidu.com", "prefix" => "user"], function ($router) {
        $router->get("pio", function () {

        });

        $router->group(["domain" => "book.com", "prefix" => "do"], function ($router) {
            $router->get("oo", function () {

            });
        });
    });
});

Helper::router()->compile();

var_dump($app->getConfig("app"));