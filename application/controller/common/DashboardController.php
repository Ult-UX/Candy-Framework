<?php
namespace App\controller\common;

use App\controller\common\SessionController;
use Candy\library\Parser;

class DashboardController extends SessionController
{
    public function __construct()
    {
        if (!isset($_SESSION)) {
            var_dump($_SESSION);
        }
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
