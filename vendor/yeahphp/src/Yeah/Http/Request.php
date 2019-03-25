<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Http;

/**
 * Http Request
 */
class Request
{
    /**
     * @var string 请求的域名或IP
     */
    protected $domain;

    /**
     * @var string 请求的主机地址
     */
    protected $host;

    /**
     * @var string 请求地址的SCHEME
     */
    protected $scheme;

    /**
     * @var string|null
     */
    protected $raw      = null;

    /**
     * @var array
     */
    protected $env      = [];

    /**
     * @var array
     */
    protected $argv     = [];

    /**
     * @var array
     */
    protected $get      = [];

    /**
     * @var array
     */
    protected $post     = [];

    /**
     * @var array
     */
    protected $request  = [];

    /**
     * @var array
     */
    protected $globals  = [];

    /**
     * @var array
     */
    protected $cookie  = [];

    /**
     * @var array
     */
    protected $server  = [];

    /**
     * @var array
     */
    protected $header  = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->env     = &$_ENV;
        $this->get     = &$_GET;
        $this->post    = &$_POST;
        $this->server  = &$_SERVER;
        $this->cookie  = &$_COOKIE;
        $this->request = &$_REQUEST;
        $this->globals = &$GLOBALS;

        if ($this->isCli()) {
            $this->argv = $this->server("argv");
        }

        $this->parseHeader();
    }

    /**
     * 解析请求头信息
     */
    protected function parseHeader()
    {
        if (function_exists("apache_request_headers")) {
            $this->header = apache_request_headers();
            return ;
        }

        foreach ($this->server as $name => $value) {
            if (substr($name, 0, 5) == "HTTP_") {
                $name = ucwords(strtolower(str_replace("_", "-", substr($name, 5))), "-");
                $this->header[$name] = $value;
            }
        }
    }

    /**
     * 返回请求主机地址,可能包含端口
     * @return string
     */
    public function host()
    {
        if (null === $this->host) {
            $this->host = $this->scheme() . "://" . $this->header("Host", "");
        }

        return $this->host;
    }

    /**
     * 返回服务器口号
     * @return int
     */
    public function port()
    {
        return $this->server("SERVER_PORT");
    }

    /**
     * 返回请求域名
     * @return string
     */
    public function domain()
    {
        if (null === $this->domain) {
            $this->domain = $this->header("Host", "");

            if ($pos = strpos($this->domain, ":")) {
                $this->domain = substr($this->domain, 0, $pos);
            }
        }

        return $this->domain;
    }

    /**
     * 返回请求地址,包含参数
     * @param boolean $intact 是否包含主机部分
     * @return string
     */
    public function url($intact = false)
    {
        $url = urldecode($this->server("REQUEST_URI", ""));

        if (true === $intact) {
            $url = $this->host() . $url;
        }

        return $url;
    }

    /**
     * 返回请求地址,不包含参数
     * @param boolean $intact 是否包含主机部分
     * @return string
     */
    public function rawUrl($intact = false)
    {
        $url = urldecode($this->server("SCRIPT_NAME", ""));

        if (true === $intact) {
            $url = $this->host() . $url;
        }

        return $url;
    }

    /**
     * 返回请求地址SCHEME(http或https)
     * @return string
     */
    public function scheme()
    {
        if (null === $this->scheme) {
            $this->scheme = $this->isHttps() ? "https" : "http";
        }

        return $this->scheme;
    }

    /**
     * 返回查询字符串
     * @return string
     */
    public function queryString()
    {
        return $this->server("QUERY_STRING", "");
    }

    /**
     * 返回查询值
     * @param string $key 键名
     * @return mixed
     */
    public function query($key = null)
    {
        parse_str($this->queryString(), $query);

        if (isset($query[$key])) {
            return $query[$key];
        }

        return null;
    }

    /**
     * 返回请求HTTP协议名称及版本
     * @return string
     */
    public function protocol()
    {
        return $this->server("SERVER_PROTOCOL");
    }

    /**
     * 返回UserAgent信息
     * @return string
     */
    public function agent()
    {
        return $this->header("User-Agent", "");
    }

    /**
     * 返回请求语言
     * @return string
     */
    public function lang()
    {
        return $this->header("Accept-Language", "");
    }

    /**
     * 返回代理IP地址,如221.5.252.160,203.98.182.163,...
     * @return string
     */
    public function proxy()
    {
        return $this->header("X-Forwarded-For", "");
    }

    /**
     * 返回请求IP地址
     * @return string
     */
    public function ip()
    {
        if ($proxy = $this->proxy()) {
            return end(explode(",", $proxy));
        }

        if ($ip = $this->header("X-Real-Ip")) {
            return $ip;
        }

        return $this->server("REMOTE_ADDR", "");
    }

    /**
     * 返回来源地址
     * @return string
     */
    public function referer()
    {
        return $this->server("HTTP_REFERER", "");
    }

    /**
     * 返回请求方法,如GET、POST
     * @return string
     */
    public function method()
    {
        return $this->server("REQUEST_METHOD");
    }

    /**
     * 返回请求时间
     * @param boolean $float
     * @return int
     */
    public function time($float = false)
    {
        return $this->server($float === true ? "REQUEST_TIME_FLOAT" : "REQUEST_TIME");
    }

    /**
     * 返回客户端使用的浏览器
     * @return string
     */
    public function browser()
    {
        return "";
    }

    /**
     * 返回客户端使用的系统
     * @return string
     */
    public function os()
    {
        return "";
    }

    /**
     * 返回$_GET请求数据
     * @param string|null $key
     * @param mixed $default
     * @return array|string
     */
    public function get($key = null, $default = null)
    {
        return $this->data("get", $key, $default);
    }

    /**
     * 返回$_POST请求数据
     * @param string|null $key
     * @param mixed $default
     * @return array|string
     */
    public function post($key = null, $default = null)
    {
        return $this->data("post", $key, $default);
    }

    /**
     * 返回$_REQUEST数据
     * @param string|null $key
     * @param mixed $default
     * @return array|string
     */
    public function request($key = null, $default = null)
    {
        return $this->data("request", $key, $default);
    }

    /**
     * 返回$GLOBALS数据
     * @param string|null $key
     * @param mixed $default
     * @return array|string
     */
    public function globals($key = null, $default = null)
    {
        return $this->data("globals", $key, $default);
    }

    /**
     * 返回$_COOKIE数据
     * @param string|null $key
     * @param mixed $default
     * @return array|string
     */
    public function cookie($key = null, $default = null)
    {
        return $this->data("cookie", $key, $default);
    }

    /**
     * 返回$_SERVER数据
     * @param string|null $key
     * @param mixed $default
     * @return array|string
     */
    public function server($key = null, $default = null)
    {
        return $this->data("server", $key, $default);
    }

    /**
     * 返回请求头信息
     * @param string|null $key
     * @param mixed  $default
     * @return array|string
     */
    public function header($key = null, $default = null)
    {
        return $this->data("header", $key, $default);
    }

    /**
     * 返回CLI模式请求参数
     * @param string|null $key
     * @param mixed  $default
     * @return array|string|null
     */
    public function argv($key = null, $default = null)
    {
        return $this->data("argv", $key, $default);
    }

    /**
     * 返回请求产生的数据
     * @param string $type 数据类型
     * @param string|null $key 键值对
     * @param string|null $default 默认值
     * @return mixed
     */
    protected function data($type, $key = null, $default = null)
    {
        $data = [];

        switch ($type) {
            case "env": $data = $this->env; break;
            case "get": $data = $this->get; break;
            case "post": $data = $this->post; break;
            case "argv": $data = $this->argv; break;
            case "cookie": $data = $this->cookie; break;
            case "server": $data = $this->server; break;
            case "header": $data = $this->header; break;
            case "request": $data = $this->request; break;
            case "globals": $data = $this->globals; break;
        }

        if (null !== $key) {
            return isset($data[$key]) ? $data[$key] : $default;
        }

        return $data;
    }

    /**
     * 返回原始数据只读流,POST请求
     * @return string
     */
    public function raw()
    {
        if (null === $this->raw) {
            $this->raw = file_get_contents("php://input");
        }

        return $this->raw;
    }

    /**
     * 返回上传文件信息
     * @param string $name
     */
    public function file($name)
    {

    }

    /**
     * 判断请求类型
     * @param string $method
     * @return boolean
     */
    public function is($method)
    {
        if (is_string($method) && $method) {
            return strtoupper($method) == $this->method();
        }

        return false;
    }

    /**
     * 判断是否AJAX请求
     * @return boolean
     */
    public function isAjax()
    {
        return $this->header("X-Requested-With") == "XMLHttpRequest";
    }

    /**
     * 判断是否PJAX请求
     * @return boolean
     */
    public function isPjax()
    {
        return $this->isAjax() && "true" == $this->header("X-Pjax");
    }

    /**
     * 判断是否是否有文件上传
     * @param string $name
     * @return boolean
     */
    public function isUpload($name = null)
    {
        if (null !== $name) {
            return isset($_FILES[$name]);
        }

        return empty($_FILES);
    }

    /**
     * 判断是否HTTPS请求
     * @return boolean
     */
    public function isHttps()
    {
        if ($https = $this->server("HTTPS")) {
            return $https == "on" || $https == 1;
        }

        if (443 == $this->port()) {
            return true;
        }

        return false;
    }

    /**
     * 判断是否CLI模式
     * @return boolean
     */
    public function isCli()
    {
        return PHP_SAPI == "cli";
    }

    /**
     * 判断是否Mobile请求
     * @return boolean
     */
    public function isMobile()
    {
        $agent = $this->agent();

        foreach (["Wap", "Android", "Mobile", "iPhone", "iPad"] as $tag) {
            if (false != stripos($agent, $tag)) {
                return true;
            }
        }

        return false;
    }
}
