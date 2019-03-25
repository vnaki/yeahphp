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
 * Router Class
 */
class Router
{
    /**
     * @var array 注册的路由
     */
    protected $routes    = [];

    /**
     * @var array 注册的404路由
     */
    protected $panics    = [];

    /**
     * @var array 全局路由过滤器
     */
    protected $filters   = [];

    /**
     * @var array 路由分组
     */
    protected $groups    = [];

    /**
     * @var array 有效的路由方法
     */
    protected $method  = ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS", "HEAD"];

    /**
     * 注册GET路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function get($path, Closure $handler, $alias = null)
    {
        return $this->route("GET", $path, $handler, $alias);
    }

    /**
     * 注册POST路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function post($path, Closure $handler, $alias = null)
    {
        return $this->route("POST", $path, $handler, $alias);
    }

    /**
     * 注册POST路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function put($path, Closure $handler, $alias = null)
    {
        return $this->route("PUT", $path, $handler, $alias);
    }

    /**
     * 注册PATCH路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function patch($path, Closure $handler, $alias = null)
    {
        return $this->route("PATCH", $path, $handler, $alias);
    }

    /**
     * 注册DELETE路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function delete($path, Closure $handler, $alias = null)
    {
        return $this->route("DELETE", $path, $handler, $alias);
    }

    /**
     * 注册OPTIONS路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function options($path, Closure $handler, $alias = null)
    {
        return $this->route("OPTIONS", $path, $handler, $alias);
    }

    /**
     * 注册HEAD路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function head($path, Closure $handler, $alias = null)
    {
        return $this->route("HEAD", $path, $handler, $alias);
    }

    /**
     * 注册多个类型路由
     * @param string  $method
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function any($method, $path, Closure $handler, $alias = null)
    {
        return $this->route(strtoupper($method), $path, $handler, $alias);
    }

    /**
     * 注册全部类型路由
     * @param string  $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    public function all($path, Closure $handler, $alias = null)
    {
        return $this->route($this->method, $path, $handler, $alias);
    }

    /**
     * 注册路由分组
     * @param array   $group
     * @param Closure $handler
     */
    public function group(array $group, Closure $handler)
    {
        array_unshift($this->groups, $group);
        $handler($this);
        array_shift($this->groups);
    }

    /**
     * 注册前缀分组
     * @param string  $prefix
     * @param Closure $handler
     */
    public function prefix($prefix, Closure $handler)
    {
        $this->group(["prefix" => $prefix], $handler);
    }

    /**
     * 注册SCHEME分组
     * @param Closure|array $scheme
     * @param Closure $handler
     */
    public function scheme($scheme, Closure $handler)
    {
        $this->group(["scheme" => $scheme], $handler);
    }

    /**
     * 注册域名分组
     * @param string|array  $domain
     * @param Closure $handler
     */
    public function domain($domain, Closure $handler)
    {
        $this->group(["domain" => $domain], $handler);
    }

    /**
     * 注册过滤器分组
     * @param Closure|array $filter
     * @param Closure $handler
     */
    public function filter($filter, Closure $handler)
    {
        $this->group(["filter" => $filter], $handler);
    }

    /**
     * 注册匹配模式分组
     * @param Closure|array $pattern
     * @param Closure $handler
     */
    public function pattern($pattern, Closure $handler)
    {
        $this->group(["pattern" => $pattern], $handler);
    }

    /**
     * 注册路由
     * @param string|array $methods
     * @param string $path
     * @param Closure $handler
     * @param string|null $alias
     * @return Route
     */
    protected function route($methods, $path, Closure $handler, $alias)
    {
        if (is_string($methods)) {
            $methods = explode("|", $methods);
        }

        $route = new Route($path, $handler);

        if ($alias) {
            $route->alias($alias);
        }

        foreach ($methods as $method) {
            $route->method($method);
        }

        foreach ($this->groups as $group) {
            $this->handleGroup($group, $route);
        }

        $this->routes[$route->getPath()] = &$route;

        return $route;
    }

    /**
     * 路由分组解析
     * @param array $group
     * @param Route $route
     */
    protected function handleGroup(array $group, Route &$route)
    {
        if (isset($group["prefix"])) {
            $route->prefix($group["prefix"]);
        }

        if (isset($group["scheme"])) {
            $route->scheme($group["scheme"]);
        }

        if (isset($group["domain"])) {
            $route->domain($group["domain"]);
        }

        if (isset($group["pattern"])) {
            $route->pattern($group["pattern"]);
        }

        if (isset($group["filter"])) {
            $route->filter($group["filter"]);
        }
    }

    /**
     * 注册404路由
     * @param string  $method
     * @param Closure $panic
     */
    public function panic($method, Closure $panic)
    {
        $this->panics[strtoupper($method)] = $panic;
    }

    /**
     * 判断HTTP方法是否有效
     * @param string $method
     * @return boolean
     */
    public function hasMethod($method)
    {
        return in_array($method, $this->method);
    }

    /**
     * 返回注册的路由
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * 返回注册的404路由
     * @return array
     */
    public function getPanics()
    {
        return $this->panics;
    }

    public function compile()
    {
        foreach ($this->routes as $route) {
            RouteCompiler::compile($route);
        }
    }
}
