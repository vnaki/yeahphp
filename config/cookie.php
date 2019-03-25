<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

return
[
    //cookie作用域名
    "domain"    => "",
    //cookie作用路径
    "path"      => "/",
    //是否只允许http协议访问cookie
    "httponly"  => true,
    //是否只允许https传送cookie
    "secure"    => false,
    //cookie过去时间,单位秒
    "expire"    => 0,
    //跨站策略,lax or strict
    "samesite"  => "lax"
];