<?php
namespace App\controller\common;

use App\controller\common\BootstrapController;
use Candy\bundle\Parser;

abstract class DashboardController extends BootstrapController
{
    public function __construct()
    {

        parent::__construct();
        $this->parser = new Parser();
        $this->parser->set(array(
            'template_dir' => APP_PATH.'view/dashboard', // 模板文件夹
            'compile_dir' => APP_PATH.'cache'.DIRECTORY_SEPARATOR.'compile', // 编译文件生成文件夹
            'tpl_suffix' => '.tpl.php', // 模板文件后缀
            'enable_cache' => 0 // 是否开启静态缓存，0 代表不开启，正整数代表缓存刷新时间，如果你正在 Debug 阶段建议关闭缓存
        ));
    }
}
