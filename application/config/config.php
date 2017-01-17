<?php
// 启用调试
$config['enable_debug'] = true;
// Application namespace
// 用用程序命名空间前缀
$config['app_namespace'] = 'App';
// 控制器后缀
$config['controller_suffix'] = 'Controller';
// 控制器方法后缀
$config['method_suffix'] = 'Action';
// 默认的控制器方法，控制器请求方法未指定时的默认请求
$config['default_method'] = 'index';
// 应用程序根目录，会影响所有 url 请求
$config['root_path'] = '/';
// url 重写后缀
$config['url_suffix'] = '.html';

// 设置用户登入会话有效期
$config['user_signed_session_life'] = 1800;

$config['view_path'] = '';

$config['language'] = 'zh-cn';

$config['valid_uri_characters'] = 'a-z 0-9~%.:_\-';
