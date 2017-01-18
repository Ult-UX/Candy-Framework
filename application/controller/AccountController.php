<?php
namespace App\controller;

use App\controller\common\PrimaryController;
use App\model\AccountModel;

class AccountController extends PrimaryController
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isSigned()) {
            $this->URL->redirect($this->URL->build('signin'), true);
        }
        $this->AccountModel = new AccountModel();
    }
    public function indexAction()
    {
        var_dump($this->isSigned());
        $this->parser->display('account/index');
    }
}
