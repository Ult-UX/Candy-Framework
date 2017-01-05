<?php
namespace APP\controller;

use APP\common\PrimaryController;

use APP\model\ContentModel;
use APP\model\MetaModel;
use Spark\libraries\Pagination;

class ContentController extends PrimaryController
{
    public function __construct()
    {
        parent::__construct();
        $this->ContentModel = new ContentModel();
        $this->MetaModel = new MetaModel();
    }
    // 默认索引页面
    public function indexAction($page = 1)
    {
        $this->view->assign('title', '所有文章');
        $contents = $this->ContentModel->getContents(array('type'=>'post', 'status'=>'publish'), $page, 6, 'created');
        $this->view->assign('contents', $contents['contents']);
        $this->view->parse('index');
    }
    // 分类文章或者标签文章索引页面
    public function metaAction($slug, $page = 1, $type = 'category')
    {
        $meta = $this->MetaModel->getBySlug($slug);
        $this->view->assign('title', $meta['name']);
        $contents = $this->ContentModel->getContentsByMeta($meta['mid'], array('status'=>'publish'), $page, 6, 'created');
        $this->view->assign('contents', $contents['contents']);
        // var_dump(ceil($contents['quantity']/6), $page);
        // 分页
        $pagination = new Pagination();
        $config['url'] = '/category/default/$page.html';
        $config['current'] = $page;
        $config['total'] = $contents['quantity'];
        $config['per_page'] = 6;
        $this->view->assign('pagination', $pagination->create_navs($config));
        $this->view->parse('index');
    }
    // 单篇文章或页面显示
    public function viewAction($slug)
    {
        $content = $this->ContentModel->getBySlug($slug);
        if ($content && $content['status'] == 'publish') {
            $content['categories'] = $this->MetaModel->getMetasByContent($content['cid']);
            $content['tags'] = $this->MetaModel->getMetasByContent($content['cid'], 'tag');
            var_dump($content);
        } else {
            die('找不到文章：'. $slug);
        }
    }
    // 搜索结果
    public function searchAction($page = 1)
    {
        $this->view->assign('title', $_GET['keywords']);
        $keywords = explode(' ', $_GET['keywords']);
        $contents = $this->ContentModel->search($keywords, $page, 6, 'created');
        $this->view->assign('contents', $contents['contents']);
        // 分页
        $pagination = new Pagination();
        $this->view->assign('pagination', $pagination->create_navs($contents['quantity'], $page));
        $this->view->parse('index');
    }
}
