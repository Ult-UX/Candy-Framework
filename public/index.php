<?php
/**
 * Candy Framework
 *
 * An open source application development framework for PHP
 *
 * This content is released under the Apache License Version 2.0
 *
 * Copyright (c) 2017, ultux.com
 *
 *
 * @package     Candy
 * @author      ult-ux@outlook.com
 * @copyright   Copyright (c) 2017, ULTUX.COM
 * @license     http://www.apache.org/licenses/	Apache License Version 2.0
 * @link        https://ultux.com
 * @since       Version 1.0.0
 */

// Require the Candy Framework Bootstrap file
// 加载 Candy Framework 引导文件
require_once './../candy/Bootstrap.php';
// Instantiate Candy
// 实例化 Candy
$candy = new Candy\Bootstrap();
// Set APP path and run it
// 设置 APP 路径，运行
$candy->run();


// var_dump($_SERVER);

// session_destroy();
// var_dump(@$_SESSION);