<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Base;

use \Exception;
use \ErrorException;

/**
 * 错误处理类
 */
class HandleException
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        error_reporting(0);
        ini_set("display_errors", 0);
    }

    /**
     * 异常错误处理
     */
    public function listen()
    {
        set_error_handler([$this, "error"]);
        set_exception_handler([$this, "exception"]);
        register_shutdown_function([$this, "shutdown"]);
    }

    /**
     * 错误处理
     * @param int    $level 错误级别
     * @param string $info  错误信息
     * @param string $file  错误文件
     * @param string $line  错误行号
     * @throws ErrorException
     */
    public function error($level, $info, $file, $line)
    {
        throw new ErrorException($info, 0, $level, $file, $line);
    }

    /**
     * 异常处理
     * @param FatalException|Exception $e
     */
    public function exception($e)
    {
        $this->display($e);
    }

    /**
     * 捕获致命错误
     * @throws ErrorException
     */
    public function shutdown()
    {
        $error = error_get_last();

        if(null !== $error && $this->isFatal($error["type"])) {
            throw new FatalException($error["message"], 0, $error["type"],  $error["file"], $error["line"]);
        }
    }

    /**
     * 判断是否致命的错误
     * @param int $level 错误级别
     * @return boolean
     */
    protected function isFatal($level)
    {
        return in_array($level, [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING]);
    }

    /**
     * 异常报告
     * @param mixed $e
     */
    protected function display($e)
    {
        echo <<<EOT
        ------------------------------Exception Message------------------------------<br/>
        = {$e->getMessage()}<br/>
        ------------------------------Exception File------------------------------<br/>
        {$e->getFile()}:{$e->getLine()}<br/>
EOT;
//        var_dump($e->getMessage(), $e->getFile(), $e->getLine());
    }
}