<?php
namespace App\controller;

use App\controller\common\PrimaryController;

class HomepageController extends PrimaryController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction($page = 1)
    {
        $this->parser->display('homepage');
    }
}
