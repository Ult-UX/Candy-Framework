<?php
namespace APP\controller\dashboard;

use APP\common\DashboardController;

class OverviewController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
        $this->view->parse('overview');
    }
}
