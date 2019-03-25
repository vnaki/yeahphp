<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Http;

/**
 * Http Cookie
 */
class Cookie
{
    /**
     * @var string Cookie名称,不能出现特殊字符串
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string 跨站发送机制,可选 Lax、Strict
     */
    protected $samesite;

    /**
     * @var string 作用域名,默认任意域名
     */
    protected $domain;

    /**
     * @var string 作用路径,默认根路径
     */
    protected $path     = "/";

    /**
     * @var int 过期时间戳,默认0直到浏览器关闭
     */
    protected $expire   = 0;

    /**
     * @var int 希望存活时间,单位秒
     */
    protected $maxAge   = 0;

    /**
     * @var boolean 是否只允许HTTPS连接传给客户端
     */
    protected $secure   = false;

    /**
     * @var boolean 是否只能通过Http协议访问Cookie
     */
    protected $httponly = true;

    /**
     * @var boolean 是否urlencode编码后发送Cookie数据
     */
    protected $raw      = false;

    /**
     * 构造函数
     * @param string $name
     * @param string $value
     * @throws Exception
     */
    public function __construct($name, $value)
    {
        if (trim($name) == $name) {
            throw new Exception("Cookie name can not be empty");
        }

        if (preg_match("/[=,;\s\t\r\n\013\014]/", $name)) {
            throw new Exception("Cookie name cannot contain a special string:" .$name);
        }

        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * @param int $second
     * @return Cookie
     */
    public function expire($second)
    {
        if (is_numeric($second) && $second > 0) {
            $this->expire = time() + $second;
            $this->maxAge = $second;
        }

        return $this;
    }

    /**
     * @param string $path
     * @return Cookie
     */
    public function path($path)
    {
        if (is_string($path) && $path) {
            $this->path = $path;
        }

        return $this;
    }

    /**
     * @param string $domain
     * @return Cookie
     */
    public function domain($domain)
    {
        if (is_string($domain) && $domain) {
            $this->domain = $domain;
        }

        return $this;
    }

    /**
     * @param string $samesite
     * @return Cookie
     */
    public function samesite($samesite)
    {
        if (in_array($samesite, ["LAX", "STRICT"])) {
            $this->samesite = $samesite;
        }

        return $this;
    }

    /**
     * @param boolean $secure
     * @return Cookie
     */
    public function secure($secure)
    {
        if (is_bool($secure)) {
            $this->secure = $secure;
        }

        return $this;
    }

    /**
     * @param boolean $httponly
     * @return Cookie
     */
    public function httponly($httponly)
    {
        if (is_bool($httponly)) {
            $this->httponly = $httponly;
        }

        return $this;
    }

    /**
     * @param boolean $raw
     * @return Cookie
     */
    public function raw($raw)
    {
        if (is_bool($raw)) {
            $this->raw = $raw;
        }

        return $this;
    }

    /**
     * 返回对象的字符串形式
     * @return string
     */
    public function __toString()
    {
        $cookie = $this->name . "=";

        if (null === $this->value) {
            $cookie .= "deleted;expires=" . gmdate("D, d-M-Y H:i:s T", time() - 31536001) . ";max-age=-31536001";
        } else {
            $cookie .= $this->raw ? $this->value : urlencode($this->value);

            if (0 < $this->expire) {
                $cookie .= ";expires=" . gmdate("D, d-M-Y H:i:s T", $this->expire).";max-age=".$this->maxAge;
            }
        }

        $cookie .= ";path=" . $this->path;

        if ($this->domain) {
            $cookie .= ";domain=" . $this->domain;
        }

        if($this->secure) {
            $cookie .= ";secure";
        }

        if($this->httponly) {
            $cookie .= ";httponly";
        }

        if($this->samesite) {
            $cookie .= ";samesite=" . $this->samesite;
        }

        return $cookie;
    }
}
