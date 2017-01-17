<?php
namespace Candy\mvc;

/**
 * 视图渲染
 *
 * @package     Candy 这是一个 PHP 框架组件库
 * @subpackage  mvc
 * @category    mvc
 * @author      ult-ux@outlook.com
 * @link        http://ultux.com
 */
class View
{
    /**
     * Config for class manipulation
     *
     * @var array
     */
    private $config = array(
                'template_dir' => APP_PATH.'view'.DIRECTORY_SEPARATOR, // 模板文件夹
                'compile_dir' => APP_PATH.'cache'.DIRECTORY_SEPARATOR.'compile'.DIRECTORY_SEPARATOR, // 编译文件生成文件夹
                'tpl_suffix' => '.php' // 模板文件后缀
            );

    /**
     * Data for class manipulation
     *
     * @var array
     */
    private $data = array();

    /**
     * Set the class configuration parameters
     * 设置解析器配置参数
     *
     * @param   array   $config Associative array
     * @return  object
     */
    public function set($config = array())
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * Assign variables to the template
     * 赋值模板
     *
     * @param   mixed   $var    The variable name
     * @param   string  $value
     * @return  object
     */
    public function assign($var, $value = null)
    {
        if (is_array($var)) {
            foreach ($var as $key=>$value) {
                return $this->assign($key, $value);
            }
        } elseif (is_string($var)) {
            $this->data[$var] = $value;
        }
        return $this;
    }

    /**
     * Output the rendered template to the browser
     * 将渲染后的模板输出到浏览器
     *
     * @param   string  $template   template name
     * @param   array   $data       Associative array
     * @return  string
     */
    public function display($template, $data = array())
    {
        echo $this->fetch($template, $data);
    }

    /**
     * Get the rendered template
     * 获取渲染后的模板
     *
     * @param   string  $template   template name
     * @param   array   $data       Associative array
     * @return  string
     */
    public function fetch($template, $data = array())
    {
        $this->assign($data);
        if (is_file($template_file = $this->config['template_dir'].$template.$this->config['tpl_suffix'])) {
            $render = $this->render($template_file);
        } else {
            $compile_file = $this->config['compile_dir'].md5($template).$this->config['tpl_suffix'];
            // var_dump($compile_file);
            file_put_contents($compile_file, $template);
            $render = $this->render($compile_file);
        }
        return $render;
    }

    /**
     * render template
     * 渲染后模板
     *
     * @param   string  $template_file  template file path
     * @return  string
     */
    private function render($template_file)
    {
        ob_start();
        foreach ($this->data as $key=>$value) {
            ${$key} = $value;
        }
        include $template_file;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
}
