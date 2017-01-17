<?php
namespace Candy\core;

final class URI
{
    private $segments = array(
        'scheme' => '',
        'host' => '',
        'port' => '',
        'path' => '',
        'query' => '',
        'script' => '',
        'prefix' => '',
        'suffix' => ''
    );

    public function __construct()
    {
        $this->init();
    }
    private function init()
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
        $this->segments['prefix'] = implode('/', array_intersect(explode('/', parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH)), explode('/', $this->segments['script'])));
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

    public function set($segments = array())
    {
        $this->segments = array_merge($this->segments, $segments);
        return $this;
    }
    public function get($segment = null)
    {
        if (!$segment) {
            return $this->segments;
        }
        return $this->segments[$segment];
    }
    public function getUrl($rewrite = true, $full = false)
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
            $url .= $this->segments['prefix'];
        }
        if ($this->segments['path'] != '/') {
            $url .= $this->segments['path'];
        }
        $url .= $this->segments['suffix'];
        if ($this->segments['query']) {
            $url .= '?'.$this->segments['query'];
        }
        return $url;
    }
}
