<?php
namespace Candy\core;

class AutoLoader
{
    public static $namespaces = array();
    public static $fallbacks = array();
    public static $missing = array();
    public static $files = array(
        'fallbacks' => array()
    );

    public static function __callStatic($name, $arguments)
    {
        $Instance = new static();
        if (!$Instance->loadFile($arguments, $name)) {
            $Instance->loadFile($arguments);
        }
    }
    public static function register()
    {
        spl_autoload_register(array(new static(), 'loadClass'));
    }
    public static function add($value, $is_classmaps = true)
    {
        $Instance = new static();
        if ($is_classmaps) {
            $Instance->addClassMaps($value);
        } else {
            $Instance->addFiles($value);
        }
    }
    private function addClassMaps($classMaps)
    {
        if (!is_array($classMaps)) {
            return $this->addClassMaps(array($classMaps));
        }
        $status = false;
        foreach ($classMaps as $key=>$value) {
            if ($this->checkMissing($value)) {
                continue;
            }
            $key = ltrim($key, '\\');
            if (is_dir($value)) {
                // 映射到 $namespaces 中
                if (is_string($key) && $key) {
                    self::$namespaces[$key] = $value;
                } else {
                    // 索引项或者 $key 为空，添加到后备文件夹 $fallbacks
                    if (!in_array($value, self::$fallbacks)) {
                        array_unshift(self::$fallbacks, $value);
                    }
                }
                $status = true;
            } elseif (is_file($value)) {
                // 取得 $class 文件名
                $class = basename($value, '.php');
                // 关联项，且 $key 不为空，$key 作为命名空间的前缀
                if (is_string($key) && $key) {
                    $class = $key.$class;
                }
                $status = $this->loadClass($class, $value);
            } else {
                self::$missing[] = $value;
            }
        }
        return $status;
    }
    private function addFiles($files)
    {
        if (!is_array($files)) {
            return $this->addFiles(array([$files]));
        }
        $status = false;
        foreach ($files as $key=>$value) {
            foreach ($value as $index=>$path) {
                if ($this->checkMissing($path)) {
                    unset($value[$index]);
                    continue;
                }
                if (is_file($path)) {
                    include_once $path;
                    unset($value[$index]);
                }
            }
            if (!$value) {
                continue;
            }
            if (is_string($key) && $key) {
                if (empty(self::$files[$key])) {
                    self::$files[$key] = $value;
                } else {
                    self::$files[$key] = array_merge($value, self::$files[$key]);
                }
            } else {
                if (!in_array($value, self::$files['fallbacks'])) {
                    array_unshift(self::$files['fallbacks'], $value);
                }
            }
        }
        return $status;
    }
    private function checkMissing($path)
    {
        // 检查 $path 是否在失效的记录中
        if (in_array($path, self::$missing)) {
            return true;
        }
        // 检查 $path 是否存在
        if (!file_exists($path) or $path == DIRECTORY_SEPARATOR) {
            self::$missing[] = $path;
            return true;
        }
    }
    private function loadFile($fileName, $group = 'fallbacks')
    {
        if (is_array($fileName)) {
            foreach ($fileName as $single) {
                return $this->loadFile($single, $group);
            }
        }
        foreach (self::$files[$group] as $dir) {
            if (file_exists($file = $dir.DIRECTORY_SEPARATOR.$fileName.'.php')) {
                return include_once $file;
            }
        }
        return false;
    }
    private function loadClass($class, $file = null)
    {
        // 兼容 PHP 5.3.0 - 5.3.2 https://bugs.php.net/50731
        $class = ltrim($class, '\\');
        // 检查类 $class 是否加载过, 避免重复加载
        if (class_exists($class)) {
            return true;
        }
        $file = ($file) ? $file : $this->findClassFile($class);
        if (!$this->checkMissing($file)) {
            include $file;
            if (class_exists($class)) {
                return true;
            }
        }
    }
    private function findClassFile($class)
    {
        foreach (self::$namespaces as $key=>$value) {
            if (0 === strpos($class, $key)) {
                if (file_exists($file = $value.strtr(substr($class, strlen($key)), '\\', DIRECTORY_SEPARATOR).'.php')) {
                    return $file;
                }
            }
        }
        foreach (self::$fallbacks as $dir) {
            if (file_exists($file = $dir.strtr($class, '\\', DIRECTORY_SEPARATOR).'.php')) {
                return $file;
            }
        }
        return false;
    }
}
