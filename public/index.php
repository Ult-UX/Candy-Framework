<?php

// 引入 Spark 核心文件
require_once './../framework/Bootstrap.php';
// 实例化 Spark 并设置 APP 路径
$candy = new Framework\Bootstrap();
// 运行
$candy->run('./../app');

echo '<pre style="background-color:#eee">';
print_r(get_included_files());
echo '</pre>';

