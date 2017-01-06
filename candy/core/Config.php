<?php
namespace Candy\core;

final class Config
{
    public static $config = array();
    private $items = array();
    private static $dirs = array();
    private static $allowed_method = array('load','get');
    // 获取 $this->items
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this, $method.'Config'), $arguments);
    }
    // 获取 self::$config
    public static function __callStatic($method, $arguments)
    {
        if (!in_array($method, self::$allowed_method)) {
            return false;
        }
        $arguments[1] = empty($arguments[1]) ? true : $arguments[1];
        return call_user_func_array(array(new static(), $method.'Config'), $arguments);
    }
    public function __get($name)
    {
        $class = new static;
        if (isset($this->items[$name])) {
            $class->items = $this->items[$name];
        }
        return $class;
    }
    public static function add($path)
    {
        if (!is_array($path)) {
            if (!file_exists($path)) {
                return false;
            }
            if (is_dir($path) && !in_array($path, self::$dirs)) {
                self::$dirs[] = realpath($path).DIRECTORY_SEPARATOR;
                return true;
            } elseif (is_file()) {
                $Instance = new static();
                return $Instance->load(basename($path, '.php'), true, realpath(dirname($path)).DIRECTORY_SEPARATOR);
            }
        } else {
            foreach ($path as $dir) {
                self::add($dir);
            }
        }
    }
    public function set($name, $value = null)
    {
        $this->items[$name] = $value;
    }
    public function getConfig($config_name, $is_global = false)
    {
        if ($is_global) {
            if (empty(self::$config[$config_name])) {
                return false;
            }
            return self::$config[$config_name];
        } else {
            if (empty($this->items[$config_name])) {
                return false;
            }
            return $this->items[$config_name];
        }
    }
    private function loadConfig($file_name, $is_global = false, $file_path = null)
    {
        if (!is_array($file_name)) {
            if ($file_path == null) {
                foreach (self::$dirs as $dir) {
                    if (is_file($dir.$file_name.'.php')) {
                        $this->load($file_name, $is_global, $dir);
                    }
                }
            } else {
                include $file_path.$file_name.'.php';
                $config_name = (isset(${$file_name})) ? $file_name : 'config';
                if (!isset(${$config_name})) {
                    return;
                }
                if ($is_global) {
                    self::$config = array_merge(self::$config, ${$config_name});
                } else {
                    if (!isset($this->items[$file_name])) {
                        $this->items[$file_name] =& ${$config_name};
                    } else {
                        $this->items[$file_name] = array_merge($this->items[$file_name], ${$config_name});
                    }
                }
            }
        } else {
            foreach ($file_name as $value) {
                $this->load($value, $is_global, $file_path);
            }
        }
    }
}
