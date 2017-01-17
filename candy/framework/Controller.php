<?php
namespace Candy\mvc;

use Candy\core\Config;
use Candy\core\Input;
use Candy\mvc\View;

abstract class Controller
{
    protected $config;
    protected $input;
    protected $view;
    public function __construct()
    {
        $this->config = new Config();
        $this->input = new Input();
        $this->view = new View();
    }
}
