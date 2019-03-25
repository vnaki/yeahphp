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

class JsonFormat implements Format
{
    /**
     * @param string $file 配置文件
     * @return array
     * @throws Exception
     */
    public static function format($file)
    {
        $config = json_decode(file_get_contents($file), true);

        if (null === $config) {
            throw new Exception("Json configuration cannot be decoded: " . $file);
        }

        return $config;
    }
}