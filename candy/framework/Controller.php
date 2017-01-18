<?php
namespace Candy\framework;

use Candy\component\Config;
use Candy\extension\URL;

use Candy\bundle\Input;

use Candy\framework\View;

abstract class Controller
{
    protected $config;
    protected $input;
    protected $URL;

    protected $view;
    public function __construct()
    {
        $this->config = new Config();
        $this->input = new Input();
        $this->URL = new URL();
        $this->view = new View();
    }
}
