<?php
namespace Candy;

use Candy\core\AutoLoader;
use Candy\core\Route;
use Candy\core\Config;

/**
 * Candy Framework Bootstrap class
 * Candy Framework 引导类
 *
 * @package Candy
 * @author  ult-ux@outlook.com
 * @link    https://ultux.com
 */
final class Bootstrap
{
    /**
    * The request string
    * uri 请求字符串
    *
    * @var string
    */
    private $uri = '/';

    /**
    * The route request
    * 路由解析后的请求
    *
    * @var mixed
    */
    private $request;

    /**
     * Define system path
     */
    public function __construct()
    {
        error_reporting(1024 | 2048 | 30719);
        // 定义 Candy Framework 内部版本号
        define('CF_VERSION', '0.0.0.20170109_alpha');
        // 定义系统路径常量
        define('SYS_PATH', __DIR__.DIRECTORY_SEPARATOR);
    }
    /**
     * Run the application
     *
     * @param   string  $app_path   应用路径
     */
    public function run($app_path = 'application')
    {
        // 定义应用路径常量
        define('APP_PATH', realpath($app_path).DIRECTORY_SEPARATOR);
        // 自动加载
        $this->autoLoad();
        // 初始化
        $this->init();
        // 设置 uri
        $this->setUri();
        // 加载路由配置文件
        AutoLoader::add(APP_PATH.'route.php', false);
        // 实例化路由
        $RUT = new Route();
        // 路由解析 uri 得到请求
        $this->request = $RUT->dispatch($this->uri);
        // 请求失败时转发到 404
        if (!$this->request) {
            $this->request = $RUT->dispatch('404');
        }
        // 处理请求到控制器&方法
        if (is_array($this->request['response']) && !is_callable($this->request['response'])) {
            $this->request['response'][0] = '\\'.Config::get('app_namespace').'\\controller\\'.$this->request['response'][0].Config::get('controller_suffix');
            $this->request['response'][0] = new $this->request['response'][0]();
            $this->request['response'][1] = (empty($this->request['response'][1])) ? Config::get('default_method').Config::get('method_suffix') : $this->request['response'][1].Config::get('method_suffix');
        }
        // 执行
        $RUT->execute($this->request);
        if (Config::get('enable_debug')) {
            var_dump($this->uri, $this->request, AutoLoader::getLoaded());
            var_dump(round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 6), memory_get_usage()/1024);
        }
    }
    /**
     * Initializes the Candy framework
     * 初始化 Candy 框架
     */
    private function init()
    {
        // declare(ticks=1);
        // AutoLoader::func('debug');
        // 注册配置文件文件夹
        Config::add(APP_PATH.'config');
        // 引入应用配置
        Config::load('config');
    }
    /**
     * Initialize autoload
     * 初始化自动加载
     */
    private function autoLoad()
    {
        // 引入自动加载类
        require_once SYS_PATH.'core'.DIRECTORY_SEPARATOR.'AutoLoader.php';
        // 注册基本命名空间
        AutoLoader::add(array(
            'Candy\\' => SYS_PATH,
            'App\\' => APP_PATH
        ));
        // 注册 Candy 公共函数库
        AutoLoader::add(array('func'=>[SYS_PATH.'function']), false);
        // 加载基础公共函数
        AutoLoader::func(['common', 'url']);
        // 加载应用自动加载设置
        AutoLoader::add(APP_PATH.'autoload.php', false);
        // 注册自动加载类
        AutoLoader::register();
    }
    /**
     * Sets the uri request string
     * 设置uri请求字符串
     */
    private function setUri()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = preg_replace('/'.preg_quote(Config::get('url_suffix')).'$/is', '', $this->uri);
        $this->uri = '/'.implode('/', array_diff_assoc(explode('/', $this->uri), explode('/', $_SERVER['SCRIPT_NAME'])));
        $this->uri = urldecode($this->uri);
    }
}
