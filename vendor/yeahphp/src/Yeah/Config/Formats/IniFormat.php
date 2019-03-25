<?php
/**
 * Config Loader Component of YeahPHP
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Config\Format;

use Yeah\Config\Format;
use Yeah\Config\Exception;

class IniFormat implements Format
{
    /**
     * @param string $file 配置文件
     * @return array
     * @throws Exception
     */
    public static function format($file)
    {
        if (false === $config = parse_ini_file($file, false)) {
            throw new Exception("Ini configuration parsing failed: " . $file);
        }

        return parse_ini_file($file, false);
    }
}