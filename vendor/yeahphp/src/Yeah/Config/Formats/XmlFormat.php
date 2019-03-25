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

class XmlFormat implements Format
{
    /**
     * @param string $file 配置文件
     * @return array
     * @throws Exception
     */
    public static function format($file)
    {
        if (false === $xml = simplexml_load_file($file)) {
            throw new Exception("Xml configuration parsing failed: " . $file);
        }

        return json_decode(json_encode($xml), true);
    }
}