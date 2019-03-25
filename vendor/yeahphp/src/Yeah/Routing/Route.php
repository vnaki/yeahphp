<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Routing;

use \Closure;

/**
 * Route Class
 */
class Route
{
    /**
     * @var string 路由路径
     */
    protected $path;

    /**
     * @var Closure 路由处理器
     */
    protected $handler;

    /**
     * @var string|null 路由别名
     */
    protected $alias       = null;

    /**
     * @var array HTTP请求方法
     */
    protected $methods     = [];

    /**
     * @var array 绑定的域名
     */
    protected $domains     = [];

    /**
     * @var array 绑定URL的scheme
     */
    protected $schemes     = [];

    /**
     * @var array 路由参数
     */
    protected $params      = [];

    /**
     * @var array 路由规则
     */
    protected $patterns    = [];

    /**
     * @var array 路由过滤器
     */
    protected $filters     = [];

    /**
     * 构造函数
     * @param string  $path
     * @param Closure $handler
     */
    public function __construct($path, Closure $handler)
    {
        $this->path = $path;
        $this->handler = $handler;
    }

    /**
     * 注册路由别名
     * @param string $alias
     * @return Route
     */
    public function alias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * 注册路径前缀
     * @param string $prefix
     * @return Route
     */
    public function prefix($prefix)
    {
        if ($prefix = trim($prefix, "/")) {
            $this->path = $prefix . "/" . $this->path;
        }

        return $this;
    }

    /**
     * 注册HTTP请求方法
     * @param string $method
     * @return Route
     */
    public function method($method)
    {
        if (!$this->hasMethod($method)) {
            $this->methods[] = $method;
        }

        return $this;
    }

    /**
     * 绑定域名
     * @param string|array $domains
     * @return Route
     */
    public function domain($domains)
    {
        $domains = array_map("strtolower", (array)$domains);

        foreach ($domains as $domain) {
            if (!in_array($domain, $this->domains)) {
                $this->domains[] = $domain;
            }
        }

        return $this;
    }

    /**
     * 绑定URL的Scheme
     * @param string $schemes
     * @return Route
     */
    public function scheme($schemes)
    {
        $schemes = array_map("strtolower", (array) $schemes);

        foreach ($schemes as $scheme) {
            if (!in_array($scheme, $this->schemes)) {
                $this->schemes[] = $scheme;
            }
        }

        return $this;
    }

    /**
     * 注册路由规则
     * @param string|array $name
     * @param string|null $value
     * @return Route
     */
    public function pattern($name, $value = null)
    {
        if (is_array($name)) {
            $this->patterns = array_merge($this->patterns, $name);
        } elseif (is_string($name) && is_string($value)) {
            $this->patterns[$name] = $value;
        }

        return $this;
    }

    /**
     * 注册路由参数
     * @param string|array $name
     * @param string|null  $value
     * @return Route
     */
    public function param($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * 注册路由过滤器
     * @param array|Closure $filters
     * @param boolean $prepend
     * @return Route
     */
    public function filter($filters, $prepend = false)
    {
        $filters = (array) $filters;

        foreach ($filters as $filter) {
            if ($filter instanceof Closure) {
                if (false === $prepend) {
                    $this->filters[] = $filter;
                } else {
                    array_unshift($this->filters, $filter);
                }
            }
        }

        return $this;
    }

    /**
     * 返回路由别名
     * @return string
     */
    public function getAlias()
    {
       return $this->alias;
    }

    /**
     * 返回匹配规则
     * @return array
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * 返回路由参数
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 返回路由中间件
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * 返回路由路径
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * 判断是否有效的SCHEME
     * @param string $scheme
     * @return boolean
     */
    public function hasScheme($scheme)
    {
        return in_array(strtolower($scheme), $this->schemes);
    }

    /**
     * 判断是否有效的请求域名
     * @param string $domain
     * @return boolean
     */
    public function hasDomain($domain)
    {
        return in_array(strtolower($domain), $this->domains);
    }

    /**
     * 判断是否有效的HTTP请求方法
     * @param string $method
     * @return boolean
     */
    public function hasMethod($method)
    {
        return in_array($method, $this->methods);
    }
}
