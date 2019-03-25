<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

/**
 * mysql数据库配置
 */
return
[
    //DNS连接
    "dsn"         => "mysql:host=127.0.0.1;port=3306;dbname=%s;charset=utf8",

    /**
     * 主机名称
     */
    "hostname"    => "127.0.0.1",

    /**
     * 连接端口号
     */
    "hostport"    => 3306,

    /**
     * 数据库名称
     */
    "database"    => "",

    /**
     * 连接编码
     */
    "charset"     => "utf8",

    /**
     * 数据库用户名称
     */
    "username"    => "",

    /**
     * 连接数据库密码
     */
    "password"    => "",

    /**
     * 数据表统一前缀
     */
    "prefix"      => "",

    /**
     * 是否分布式部署,false:单机部署,true:主从分布式部署
     */
    "distributed" => false,

    /**
     * 是否读写分离
     */
    "rw"  => true,
    /**
     * 主数据库地址
     */
    "master"      => [],

    /**
     * 从数据库地址
     */
    "slave"       => [],
];