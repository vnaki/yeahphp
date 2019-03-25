<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

use Yeah\Loader\ClassLoader;


/**
 * 引入`ClassLoader`类自动加载器文件
 */
require $basePath . "/vendor/yeahphp/src/Yeah/Loader/ClassLoader.php";

/**
 * 创建类加载器
 */
$classLoader = new ClassLoader();

/**
 * 添加`Yeah`命名空间
 */
$classLoader->addPsr4("Yeah", $basePath . "/vendor/yeahphp/src/Yeah");

/**
 * 添加`App`命名空间
 */
$classLoader->addPsr4("App", $basePath . "/application");

/**
 * 载入内核类的映射文件,有助于类库文件快速查找
 */
$classLoader->addClassMapFromFile(__DIR__ . "/classmap.php");

/**
 * 注册类自动加载器
 */
$classLoader->register();

/**
 * 返回类加载器实例
 */
return $classLoader;