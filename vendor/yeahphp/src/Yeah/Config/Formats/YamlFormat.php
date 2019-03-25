<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Config\Format;

use Yeah\Config\Format;
use Yeah\Config\Exception;

class YamlFormat implements Format
{
    /**
     * @param string $file 配置文件
     * @return array
     * @throws Exception
     */
    public static function format($file)
    {
        if (false === $yaml = yaml_parse_file($file, 0)) {
            throw new Exception("Yaml configuration parsing failed: " . $file);
        }

        return $yaml;
    }
}