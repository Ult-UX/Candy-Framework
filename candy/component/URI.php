<?php
namespace Candy\component;

final class URI
{
    private $segments = array(
        'scheme' => 'http',
        'host' => '',
        'port' => '80',
        'path' => '/',
        'query' => '',
        'script' => '/index.php',
        'root' => '/',
        'suffix' => '.html'
    );

    public function __construct()
    {
        $this->initialize();
    }

    public function set($segments = array())
    {
        if (is_string($segments)) {
            $segments = parse_url($segments);
            if (isset($segments['path'])) {
                if (preg_match('/\.[a-z]+$/is', $segments['path'], $matches)) {
                    $segments['suffix'] = $matches[0];
                    $segments['path'] = substr($segments['path'], 0, - strlen($matches[0]));
                }
            }
        }
        $this->segments = array_merge($this->segments, $segments);
        return $this;
    }

    public function segment($segment = null)
    {
        if (!$segment) {
            return $this->segments;
        }
        return $this->segments[$segment];
    }
    public function get($rewrite = true, $full = false)
    {
        $url = '';
        if ($full) {
            $url .= $this->segments['scheme'].':'.'//';
            $url .= $this->segments['host'];
            if ($this->segments['port'] != '80') {
                $url .= ':'.$this->segments['port'];
            }
        }
        if (!$rewrite) {
            $url .= $this->segments['script'];
        } else {
            if ($this->segments['root'] != '/') {
                $url .= $this->segments['root'];
            }
        }
        if ($this->segments['path'] != '/') {
            $url .= $this->segments['path'];
            $url .= $this->segments['suffix'];
        }
        if ($this->segments['query']) {
            $url .= '?'.$this->segments['query'];
        }
        if (!$url) {
            $url = '/';
        }
        return $url;
    }

    private function initialize()
    {
        //判定是否是IIS7 并且赋值$_SERVER['REQUEST_URI']
        if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
            $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
        }
        //如果不是IIS7 判定是否是IIS6 并且赋值$_SERVER['REQUEST_URI']
        elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
        }
        // 设置当前脚本路径
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $this->segments['script'] = $_SERVER['SCRIPT_NAME'];
        } elseif (isset($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT'])) {
            $this->segments['script'] = substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']));
        }

        // 设置协议
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            $this->segments['scheme'] = $_SERVER['REQUEST_SCHEME'];
        } elseif (isset($_SERVER['SERVER_PROTOCOL'])) {
            $this->segments['scheme'] = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
        }

        // 设置主机
        if (isset($_SERVER['SERVER_NAME'])) {
            $this->segments['host'] = $_SERVER['SERVER_NAME'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            $this->segments['host'] = $_SERVER['HTTP_HOST'];
        }

        // 设置端口
        if (isset($_SERVER['SERVER_PORT'])) {
            $this->segments['port'] = $_SERVER['SERVER_PORT'];
        }

        // 设置 path
        if (isset($_SERVER['PATH_INFO'])) {
            $this->segments['path'] = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['PHP_SELF'])) {
            $this->segments['path'] = $_SERVER['PHP_SELF'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $this->segments['path'] = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
        }
        $this->segments['root'] = implode('/', array_intersect(explode('/', parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH)), explode('/', $this->segments['script'])));
        $this->segments['path'] = array_diff_assoc(explode('/', $this->segments['path']), explode('/', $this->segments['script']));
        if (!$this->segments['suffix']) {
            $this->segments['suffix'] = strrchr(end($this->segments['path']), '.');
        }
        if ($this->segments['suffix']) {
            $this->segments['path'][] = preg_replace('/'.preg_quote($this->segments['suffix']).'$/is', '', array_pop($this->segments['path']));
        }
        $this->segments['path'] = '/'.implode('/', $this->segments['path']);
        
        // 设置查询
        if (isset($_SERVER['QUERY_STRING'])) {
            $this->segments['query'] = urldecode($_SERVER['QUERY_STRING']);
        }

        // 强制类型转换
        $this->segments = array_map(function ($value) {
            return (string) $value;
        }, $this->segments);
    }
}
