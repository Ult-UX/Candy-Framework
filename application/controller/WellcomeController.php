<?php
namespace App\controller;

use Candy\mvc\Controller;

class WellcomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $this->view->display('welcome_message');
    }
}
