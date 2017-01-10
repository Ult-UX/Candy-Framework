<?php
namespace App\controller\dashboard;

use App\controller\common\DashboardController;

class OverviewController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
        $this->view->display('overview');
    }
}
