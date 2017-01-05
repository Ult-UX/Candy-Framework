<?php
namespace Candy\mvc;

use Candy\mvc\View;

abstract class Controller
{
    public function __construct()
    {
        $this->view = new View();
    }
}
