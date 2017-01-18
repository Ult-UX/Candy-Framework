<?php
namespace Candy\extension;

use Candy\component\URI;

class URL
{
    public function __construct()
    {
    }
    /**
     * 重定向
     */
    public function redirect($url='/', $send_origin = false, $rewrite = true)
    {
        $URI = new URI();
        if ($send_origin) {
            $query['origin'] = $this->current();
            $query = (isset($url['query'])) ? parse_str($url['query']) : array();
        }
        $url['query'] = http_build_query($query);
        $redirect = $URI->set($url)->get($rewrite, true);
        var_dump($url);
        var_dump($redirect);
        // header('Location: '.$redirect);
        exit;
    }
    public function current($rewrite = true, $full = true)
    {
        $URI = new URI();
        return $URI->getUrl($rewrite, $full);
    }
    /**
     * 构造 URL
     */
    public function build($path = '', $args = array(), $query = null, $full = false, $rewrite = true)
    {
        if ($path) {
            $path_array = explode('/', $path);
            foreach ($path_array as $key=>$value) {
                if (strpos($value, '$') === 0) {
                    if (isset($args[substr($value, 1)])) {
                        $path_array[$key] = @$args[substr($value, 1)];
                    } else {
                        if ($value !== end($path_array)) {
                            trigger_error('参数 ['.$value.'] 未定义');
                        } else {
                            unset($path_array[$key]);
                        }
                    }
                }
            }
            $path = implode('/', $path_array);
        }
        if (is_array($query)) {
            $query = http_build_query($query);
        }
        $segments = array(
            'path' => '/'.$path,
            'query' => $query
        );
        $URI = new URI();
        var_dump($segments, $URI->segment());
        $url = $URI->set($segments)->get($rewrite, $full);
        return $url;
    }
}
