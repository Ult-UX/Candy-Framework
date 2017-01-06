<?php
namespace Candy\mvc;

use Candy\mvc\View;

abstract class Controller
{
    protected $view;
    public function __construct()
    {
        $this->view = new View();
    }
}
