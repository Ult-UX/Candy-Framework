<?php
namespace Candy\core;

class Config
{
    private static $config = array();
    private $items = array();
    private static $dirs = array();
    // 获取 $this->items
    public function __call($method, $arguments)
    {
        if ($method !== 'get' or !isset($this->items[$arguments[0]])) {
            return false;
        }
        return $this->items[$arguments[0]];
    }
    // 获取 self::$config
    public static function __callStatic($method, $arguments)
    {
        if ($method !== 'get' or !isset(self::$config[$arguments[0]])) {
            return false;
        }
        return self::$config[$arguments[0]];
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
    private function load($file_name, $is_global = false, $file_path = null)
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
