<?php
/**
 * YeahPHP - A Component-based Framework
 *
 * @author wuquanyao <git@yeahphp.com>
 * @link   http://www.yeahphp.com
 */

namespace Yeah\Loader;

/**
 * Class Loader
 */
class ClassLoader
{
    /**
     * @var array PSR-0前缀
     */
    protected $psr0     = [];

    /**
     * @var array PSR-4前缀
     */
    protected $psr4     = [];

    /**
     * @var array 类的映射定义
     */
    protected $classMap = [];

    /**
     * @var boolean 是否抛出异常
     */
    protected $throw    = true;

    /**
     * @var boolean 是否放在队列之首
     */
    protected $prepend  = false;

    /**
     * 构造函数
     * @param boolean $throw
     * @param boolean $prepend
     */
    public function __construct($throw = true, $prepend = false)
    {
        $this->throw   = $throw;
        $this->prepend = $prepend;
    }

    /**
     * 注册自动加载
     * @return ClassLoader
     * @see http://www.php.net/manual/zh/function.spl-autoload-register.php
     */
    public function register()
    {
        spl_autoload_register([$this, "loadClass"], $this->throw, $this->prepend);
        return $this;
    }

    /**
     * 注销自动加载
     * @return ClassLoader
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, "loadClass"]);
        return $this;
    }

    /**
     * 添加PSR-0前缀
     * @param string  $prefix
     * @param string  $directory
     * @param boolean $prepend
     * @return ClassLoader
     */
    public function addPsr0($prefix, $directory, $prepend = false)
    {
        $directory = rtrim($directory, "/"). "/";
        $prefix    = trim($prefix, "\\") . "\\";

        if (!isset($this->psr0[$prefix])) {
            $this->psr0[$prefix] = [];
        }

        if (true === $prepend) {
            array_unshift($this->psr0[$prefix], $directory);
        } else {
            $this->psr0[$prefix][] = $directory;
        }

        return $this;
    }

    /**
     * 添加PSR-4前缀
     * @param string  $prefix
     * @param string  $directory
     * @param boolean $prepend
     * @return ClassLoader
     */
    public function addPsr4($prefix, $directory, $prepend = false)
    {
        $directory = rtrim($directory, "/"). "/";
        $prefix    = trim($prefix, "\\") . "\\";

        if (!isset($this->psr4[$prefix])) {
            $this->psr4[$prefix] = [];
        }

        if (true === $prepend) {
            array_unshift($this->psr4[$prefix], $directory);
        } else {
            $this->psr4[$prefix][] = $directory;
        }

        return $this;
    }

    /**
     * 添加类的映射
     * @param string $class 类名称
     * @param string $file  文件路径
     * @return ClassLoader
     * @throws Exception
     */
    public function addClassMap($class, $file)
    {
        if (!is_file($file)) {
            throw new Exception("Not found file: " . $file);
        }

        $this->classMap[trim($class, "\\")] = $file;
        return $this;
    }

    /**
     * 从文件中读取类的映射
     * @param string $file
     * @return ClassLoader
     * @throws Exception
     */
    public function addClassMapFromFile($file)
    {
        if (!is_file($file)) {
            throw new Exception("Not found classmap file: " . $file);
        }

        $classMap = include $file;

        if (is_array($classMap)) {
            foreach ($classMap as $class => $file) {
                $this->addClassMap($class, $file);
            }
        }

        return $this;
    }

    /**
     * 加载类对应的文件
     * @param string $class
     * @return boolean
     */
    public function loadClass($class)
    {
        if (isset($this->classMap[$class])) {
            loadFile($this->classMap[$class]);
            return true;
        }

        $namespace = $class;

        while (false !== $pos = strrpos($namespace, "\\")) {
            $namespace = substr($class, 0, $pos + 1);
            $className = substr($class, $pos + 1);

            if (false !== $file = $this->getClassFile($namespace, $className)) {
                loadFile($file);
                return true;
            }

            $namespace = rtrim($namespace, "\\");
        }

        return false;
    }

    /**
     * 返回对应类的文件,按PSR4、PSR0依次解析
     * @param string $namespace 命名前缀
     * @param string $className 类的名称
     * @return string|boolean
     */
    protected function getClassFile($namespace, $className)
    {
        $className = str_replace("\\", "/", $className) . ".php";

        if (isset($this->psr4[$namespace])) {
            foreach ($this->psr4[$namespace] as $path) {
                $file = $path . "/" . $className;
                if(file_exists($file)) {
                    return $file;
                }
            }
        }

        if (isset($this->psr0[$namespace])) {
            foreach ($this->psr0[$namespace] as $path) {
                $file = $path . "/" . str_replace("_", "/", $namespace . "/" . $className);
                if(file_exists($file)) {
                    return $file;
                }
            }
        }

        return false;
    }
}

/**
 * 隔离加载文件
 * @param string $file
 */
function loadFile($file)
{
    require $file;
}