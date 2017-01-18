<?php
namespace Candy;

use Candy\component\AutoLoader;
use Candy\component\Route;
use Candy\component\Config;
use Candy\component\URI;
use Candy\component\Error;

/**
 * Candy Framework Bootstrap class
 * Candy Framework 引导类
 *
 * @package Candy
 * @subpackage  None
 * @category    None
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
        // 检查 PHP 版本
        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            trigger_error('The current PHP version is too low, the minimum version requirements for the 5.3.0 .');
        }
        // error_reporting(1024 | 2048 | 30719);
        // 定义 Candy Framework 内部版本号
        define('CF_VERSION', '0.0.0.20170110_alpha');
        // 定义系统路径常量
        define('SYS_PATH', __DIR__.DIRECTORY_SEPARATOR);
    }
    /**
     * Run the application
     *
     * @param   string  $app_path   应用路径
     */
    public function run($app_path = './../application')
    {
        // var_dump(realpath($app_path));
        // 定义应用路径常量
        define('APP_PATH', realpath($app_path).DIRECTORY_SEPARATOR);
        // 自动加载
        $this->autoLoad();
        // 初始化
        $this->initialize();
        
        // 设置 uri
        $URI = new URI();

        $this->uri = $URI->set(array('root' => Config::get('url_root'), 'suffix' => Config::get('url_suffix')))->segment('path');
        var_dump($URI->segment());
        var_dump($URI->get());
        var_dump($URI->get(false, true));
        
        // 加载路由配置文件
        AutoLoader::add(APP_PATH.'initialize/route.php', false);
        // 实例化路由
        $RUT = new Route();
        // 路由解析 uri 得到请求
        $this->request = $RUT->dispatch($this->uri);
        // 请求失败时转发到 404
        if (!$this->request) {
            $this->request = $RUT->dispatch('404');
        }
        // Debug
        if (Config::get('enable_debug')) {
            // var_dump($this->uri, $this->request);
            // var_dump(AutoLoader::getLoaded());
        //     var_dump(round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 6), memory_get_usage()/1024);
        }
        // 处理请求到控制器&方法
        if (is_array($this->request['response']) && !is_callable($this->request['response'])) {
            $this->request['response'][0] = '\\'.Config::get('app_namespace').'\\controller\\'.$this->request['response'][0].Config::get('controller_suffix');
            $this->request['response'][0] = new $this->request['response'][0]();
            $this->request['response'][1] = (empty($this->request['response'][1])) ? Config::get('default_method').Config::get('method_suffix') : $this->request['response'][1].Config::get('method_suffix');
        }
        // 执行
        $RUT->execute($this->request);
        // $this->debug();
    }
    /**
     * Initializes the application
     * 初始化应用程序
     */
    private function initialize()
    {
        // 加载应用自动加载设置
        AutoLoader::add(APP_PATH.'initialize/autoload.php', false);
        // 注册配置文件文件夹
        Config::add(APP_PATH.'config');
        // 引入应用配置
        Config::load('config');
        // 注册应用程序命名空间
        AutoLoader::add(array(
            Config::get('app_namespace').'\\' => APP_PATH
        ));
    }
    /**
     * Initialize autoload
     * 初始化自动加载
     */
    private function autoLoad()
    {
        // 引入自动加载类
        require_once SYS_PATH.'component'.DIRECTORY_SEPARATOR.'AutoLoader.php';
        // 注册自动加载类
        AutoLoader::register();
        // 注册基本命名空间
        AutoLoader::add(array(
            'Candy\\' => SYS_PATH
        ));
        // 注册 Candy 公共函数库
        AutoLoader::add(array('func'=>[SYS_PATH.'function']), false);
        // 加载基础公共函数
        AutoLoader::func(['common', 'url']);
    }
    public function debug()
    {
        $debug_info = array(
            'uri' => $this->uri,
            'request' => $this->request,
            'autoloaded' => AutoLoader::getLoaded(),
            'time_elapsed' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 6),
            'memory_usage' => memory_get_usage()/1024
        );
        $GLOBALS['candy_framework_debug'] =& $debug_info;
    }
}
