<?php
namespace App\controller\common;

use Candy\mvc\Controller;
use Candy\library\Parser;

abstract class PrimaryController extends Controller
{
    protected $parser;

    public function __construct()
    {
        parent::__construct();
        $this->parser = new Parser();
        $this->parser->set(array(
            'template_dir' => APP_PATH.'view'.DIRECTORY_SEPARATOR, // 模板文件夹
            'compile_dir' => APP_PATH.'cache'.DIRECTORY_SEPARATOR.'compile'.DIRECTORY_SEPARATOR, // 编译文件生成文件夹
            'tpl_suffix' => '.tpl', // 模板文件后缀
            'enable_cache' => 0 // 是否开启静态缓存，0 代表不开启，正整数代表缓存刷新时间，如果你正在 Debug 阶段建议关闭缓存
        ));
    }
}
