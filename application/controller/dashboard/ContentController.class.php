<?php
namespace APP\controller\dashboard;


use APP\common\DashboardController;
use APP\model\ContentModel;
class ContentController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        $this->ContentModel = new ContentModel();
    }
    public function indexAction()
    {
        # code...
    }
    public function createAction()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            // var_dump($_POST);
            $data = $_POST;
            $handle = $this->ContentModel->create($data);
        }
        $this->view->parse('content/form');
    }
}
