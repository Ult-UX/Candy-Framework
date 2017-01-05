<?php
namespace App;

use Candy\core\AutoLoader;

// 注册基本加载
AutoLoader::add(array(
    'App\\library\\' => APP_PATH.'library'.DIRECTORY_SEPARATOR
));
AutoLoader::add(array('func'=>[APP_PATH.'function'.DIRECTORY_SEPARATOR]), false);
