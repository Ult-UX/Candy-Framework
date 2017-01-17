<?php
namespace App\controller;

use App\controller\common\PrimaryController;
use App\model\AccountModel;

class AccountController extends PrimaryController
{
    public function __construct()
    {
        parent::__construct();
        $this->AccountModel = new AccountModel();
    }
    public function indexAction($name)
    {
        var_dump($this->AccountModel->get($name));
        $this->parser->display('account/index');
    }
}
