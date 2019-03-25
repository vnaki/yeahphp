<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Http;

class Response
{
    /**
     * @var int
     */
    protected $status   = 200;

    /**
     * @var string
     */
    protected $content  = "";

    /**
     * @var array
     */
    protected $header   = [];

    /**
     * @var array
     */
    protected $cookie   = [];

    /**
     * @var string Content-Type
     */
    protected $type     = "text/html;charset=utf-8";

    /**
     * 构造函数
     * @param string $content 响应内容
     * @param int    $status  状态码
     * @param array  $header  响应头
     */
    public function __construct($content, $status = 200, array $header = [])
    {
        $this->content($content);
        $this->status($status);

        foreach ($header as $key => $value) {
            $this->header($key, $value);
        }
    }

    /**
     * 设置响应头
     * @param string $key
     * @param string $value
     * @return Response
     */
    public function header($key, $value)
    {
        $this->header[$key] = $value;
        return $this;
    }

    /**
     * 设置状态码
     * @param int $status
     * @return Response
     */
    public function status($status)
    {
        if (is_numeric($status)) {
            $this->status = $status;
        }

        return $this;
    }

    /**
     * 设置响应内容
     * @param string $content
     * @return Response
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 设置响应内容类型
     * @param string $type
     * @return Response
     */
    public function type($type)
    {
        $this->header("Content-Type", $type);
        return $this;
    }

    /**
     * 设置响应COOKIE
     * @param string $name
     * @param string $value
     * @return Cookie
     * @throws Exception
     */
    public function cookie($name, $value)
    {
        return $this->cookie[$name] = new Cookie($name, $value);
    }

    /**
     * 响应输出
     * @throws Exception
     */
    public function send()
    {
        if(!headers_sent()) {
            throw new Exception("Response header has been sent");
        }

        foreach ($this->cookie as $cookie) {
            header("Set-Cookie:" . strval($cookie));
        }

        foreach($this->header as $key => $value) {
            header($key . ":" . $value);
        }

        http_response_code($this->status);

        echo $this->content;

        $this->flush();
    }

    /**
     * 冲刷所有给客户端
     * @return void
     */
    protected function flush()
    {
        if (function_exists("fastcgi_finish_request")) {
            fastcgi_finish_request();
        }

        while (ob_get_level()) {
            ob_end_flush();
        }

        flush();
    }
}
