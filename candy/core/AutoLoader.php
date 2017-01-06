<?php
namespace Candy\core;

final class AutoLoader
{
    private static $namespaces = array(
        'fallbacks' => array()
    );
    
    private static $files = array(
        'fallbacks' => array()
    );
    private static $missing = array();

    /**
     * 记录已加载的类和文件
     */
    private static $loaded = array(
        'classes' => array(),
        'files' => array()
    );

    /**
     * 使用静态方法分类加载文件（函数库）
     */
    public static function __callStatic($method, $arguments)
    {
        $Instance = new static();
        if (!$status = $Instance->loadFile($arguments[0], $method)) {
            $status = $Instance->loadFile($arguments[0]);
        }
        return $status;
    }

    /**
     * 注册类库自动加载方法
     */
    public static function register()
    {
        spl_autoload_register(array(new static(), 'loadClass'));
    }

    /**
     * 添加路径或映射
     */
    public static function add($value, $is_namespaces = true)
    {
        $Instance = new static();
        if ($is_namespaces) {
            $Instance->addNamespaces($value);
        } else {
            $Instance->addFiles($value);
        }
    }

    /**
     * 获取已加载
     */
    public static function getLoaded()
    {
        return self::$loaded;
    }
    /**
     * 添加 Namespace 映射
     */
    private function addNamespaces($namespaces)
    {
        // 转为数组方式添加
        if (!is_array($namespaces)) {
            return $this->addNamespaces(array($namespaces));
        }
        // 设定默认状态
        $status = false;
        // 遍历参数数组
        foreach ($namespaces as $key=>$value) {
            // 跳过失效的条目
            if ($this->checkMissing($value)) {
                continue;
            }
            // 去掉开头的反斜线
            $key = ltrim($key, '\\');

            // 判断是文件夹或者文件：文件夹映射到 self::$namespaces 中；文件直接加载；其他状况（文件或者路径无效）添加到失效记录中
            if (is_dir($value)) {
                $value = realpath($value).DIRECTORY_SEPARATOR;
                // 通过 $key 进行归类，有效字符串添加映射，索引项或者 $key 为空，添加到后备文件夹 self::$namespaces['fallbacks']
                if (is_string($key) && $key) {
                    self::$namespaces[$key] = $value;
                } else {
                    if (!in_array($value, self::$namespaces['fallbacks'])) {
                        array_unshift(self::$namespaces['fallbacks'], $value);
                    }
                }
                $status = true;
            } elseif (is_file($value)) {
                $value = realpath($value);
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

    /**
     * 添加文件映射
     */
    private function addFiles($files)
    {
        // 转为数组方式添加
        if (!is_array($files)) {
            return $this->addFiles(array([$files]));
        }
        $status = false;
        foreach ($files as $key=>$value) {
            // 遍历处理 $value
            foreach ($value as $index=>$path) {
                // 检视路径是否有效，无效跳过
                if ($this->checkMissing($path)) {
                    unset($value[$index]);
                    continue;
                }
                $path = realpath($path);
                // 文件自动加载
                if (is_file($path)) {
                    $status = include_once $path;
                    self::$loaded['files'][] = $path;
                    unset($value[$index]);
                }
                $value[$index] = $path.DIRECTORY_SEPARATOR;
            }
            // 处理完的 $value 如果已经清空就跳过处理
            if (!$value) {
                continue;
            }
            // 通过 $key 进行归类，有效字符串添加映射，索引项或者 $key 为空，添加到后备文件夹 self::$files['fallbacks']
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
            $status = true;
        }
        return $status;
    }

    /**
     * 检查路径是否有效
     */
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

    /**
     * 加载文件
     */
    private function loadFile($files, $group = 'fallbacks')
    {
        if (!is_array($files)) {
            return $this->loadFile(array($files), $group);
        }
        $status = false;
        foreach ($files as $file) {
            foreach (self::$files[$group] as $dir) {
                // 检视文件是否存在，如果存在就引入，然后返回引入结果状态
                if (is_file($file = $dir.$file.'.php')) {
                    $status = include_once $file;
                    self::$loaded['files'][] = $file;
                }
            }
        }
        return $status;
    }

    /**
     * 加载类
     */
    private function loadClass($class, $file = null)
    {
        // 兼容 PHP 5.3.0 - 5.3.2 https://bugs.php.net/50731
        $class = ltrim($class, '\\');
        // 检查类 $class 是否加载过, 避免重复加载
        if (class_exists($class)) {
            return true;
        }
        // 如果没有定义类所在文件将从映射中查找
        if (!$file) {
            $file = $this->findClassFile($class);
        }
        // 确保文件没有失效
        if (!$this->checkMissing($file)) {
            include $file;
            self::$loaded['files'][] = $file;
            // 检视类是否存在，如果不存在将文件添加到失效记录，如果存在将类添加到已加载记录
            if (class_exists($class)) {
                self::$loaded['classes'][] = $class;
                return true;
            } else {
                self::$missing[] = $file;
            }
        }
    }

    /**
     * 遍历 self::$namespaces 查找类文件
     */
    private function findClassFile($class)
    {
        foreach (self::$namespaces as $key => $value) {
            // 先对应命名空间查找，如果没有再从 fallbacks 中查找
            if (0 === strpos($class, $key)) {
                if (is_file($file = $value.strtr(substr($class, strlen($key)), '\\', DIRECTORY_SEPARATOR).'.php')) {
                    return $file;
                }
            } elseif ($key == 'fallbacks') {
                foreach ($value as $dir) {
                    if (is_file($file = $dir.strtr($class, '\\', DIRECTORY_SEPARATOR).'.php')) {
                        return $file;
                    }
                }
            }
        }
    }
}
