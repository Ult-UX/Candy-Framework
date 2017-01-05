<?php
namespace Framework;

use Candy\core\AutoLoader;
use Candy\core\Route;
use Candy\core\Config;

/**
 * 框架核心引导文件
 *
 * @package     Framework
 * @author      ult-ux@outlook.com
 * @link        http://example.com
 */
final class Bootstrap
{
    private $uri = '/';
    public function __construct()
    {
        // 定义系统路径常量
        define('SYS_PATH', __DIR__.DIRECTORY_SEPARATOR);
    }
    public function run($app = 'app')
    {
        // 定义应用路径常量
        define('APP_PATH', realpath($app).DIRECTORY_SEPARATOR);
        // 初始化
        $this->init();
        // 设置 uri
        $this->setUri();
        // 加载路由配置文件
        AutoLoader::add(APP_PATH.'route.php', false);
        // 实例化路由
        $RUT = new Route();
        // 路由解析 uri 得到请求
        $request = $RUT->dispatch($this->uri);
        // 请求失败时转发到 404
        if (!$request) {
            $request = $RUT->dispatch('404');
        }
        // 处理请求到控制器&方法
        if (is_array($request['response']) && !is_callable($request['response'])) {
            $request['response'][0] = '\\App\\controller\\'.$request['response'][0].'Controller';
            $request['response'][0] = new $request['response'][0]();
            $request['response'][1] = (empty($request['response'][1])) ? 'indexAction' : $request['response'][1].'Action';
        }
        // 执行
        $RUT->execute($request);
    }
    public function init()
    {
        // 自动加载
        $this->autoLoad();
        // 注册配置文件文件夹
        Config::add(APP_PATH.'config'.DIRECTORY_SEPARATOR);
        Config::load('config');
        // 实例化配置类
        $this->config = new Config();
        // 引入应用配置
        $this->config->load('config');


        var_dump(Config::$items);
    }
    public function autoLoad()
    {
        // 引入自动加载类
        require_once SYS_PATH.'core'.DIRECTORY_SEPARATOR.'AutoLoader.php';
        // 注册基本命名空间
        AutoLoader::add(array(
            'Candy\\' => SYS_PATH,
            'App\\' => APP_PATH
        ));
        // 注册 Candy 公共函数库
        AutoLoader::add(array('func'=>[SYS_PATH.'function'.DIRECTORY_SEPARATOR]), false);
        // 加载基础公共函数
        AutoLoader::func('common');
        // 加载应用自动加载设置
        AutoLoader::add(APP_PATH.'autoload.php', false);
        // 注册自动加载类
        AutoLoader::register();
    }
    public function setUri()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = preg_replace('/'.preg_quote($this->config->item('url_suffix')).'$/is', '', $this->uri);
        $this->uri = '/'.implode('/', array_diff_assoc(explode('/', $this->uri), explode('/', $_SERVER['SCRIPT_NAME'])));
        $this->uri = urldecode($this->uri);
    }
}
