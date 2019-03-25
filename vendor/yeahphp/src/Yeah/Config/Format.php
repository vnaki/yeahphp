<?php
/**
 * Config Loader Component of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Config;

/**
 * Config Formats Interface
 */
interface Format
{
    /**
     * @param string $file 配置文件
     * @return array
     */
    public static function format($file);
}