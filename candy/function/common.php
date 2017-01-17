<?php

function foo($a, $b)
{
    var_dump(__FUNCTION__, $a, $b);
}
function url($path, $args = array(), $query = array(), $full = false, $rewrite = true)
{
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
    if ($query) {
        $query = '?'.http_build_query($query);
    } else {
        $query = '';
    }
    $segments = array(
        'path' => '/'.$path,
        'query' => (string) $query,
        'prefix' => '',
        'suffix' => '.html'
    );
    $URI = new URI();
    $url = $URI->set($segments)->getUrl($rewrite, $full);
    return $url;
}
