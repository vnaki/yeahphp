<?php
/**
 * Config Loader Component of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Config\Format;

use Yeah\Config\Format;

class ArrayFormat implements Format
{
    /**
     * @param string $file 配置文件
     * @return array
     */
    public static function format($file)
    {
        $config = include $file;

        if (is_array($config)) {
            return $config;
        }

        return [];
    }
}