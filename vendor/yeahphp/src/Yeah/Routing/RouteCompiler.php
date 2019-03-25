<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Routing;

/**
 * RouteCompiler Class
 */
class RouteCompiler implements ICompiler
{
    /**
     * @var string 匹配模式正则
     */
    const MATCH_PARAMETERS_REGEX = "/\:([^\/]+)/";

    /**
     * 编译当前路由
     * @param Route $route
     */
    public static function compile(Route $route)
    {
        $parameter = static::getMatchParameter($route);
        var_dump($parameter);
        $data = static::getCompiledRegex($route, $parameter);
    }

    /**
     * 返回匹配参数
     * @param Route $route
     * @return array
     */
    protected static function getMatchParameter(Route $route)
    {
        preg_match_all(static::MATCH_PARAMETERS_REGEX, $route->getPath(), $match);
        return $match;
    }

    /**
     * 返回匹配正则
     * @param Route $route
     * @param array $matched
     */
    protected static function getCompiledRegex(Route $route, array $matched)
    {
        foreach ($matched as $name) {

        }
    }

    /**
     * 返回匹配正则表达式
     * @param Route $route
     * @return string|null
     */
    protected function regex(Route $route)
    {
        $path = $route->getPath();

        if(false !== preg_match_all("/\:([^\/]+)/", $path, $match))
        {
            $patterns = $route->getPatterns();
            $convert  = ["/" => "\\/"];
            $keys     = [];

            foreach ($match[1] as $index => $name)
            {
                if(isset($patterns[$name]))
                {
                    $convert[$keys[$index] = $match[0][$index]] = "(" . $patterns[$name] . ")";
                }
            }

            $path = "/" . str_replace(array_keys($convert), array_values($convert), $path) . "/";

            if(false === $this->strict)
            {
                $path .= "i";
            }

            return $path;
        };

        return null;
    }
}
