<?php

/**
 * 定义`__ROOT__`常量
 */
define("__ROOT__", dirname(__DIR__));

/**
 * 定义`__TIME__`常量, 记录框架开始时间戳
 */
define("__TIME__", time());

/**
 * 引入应用类文件，创建应用实例
 */
require dirname(__DIR__) . "/ready/application.php";