<?php
namespace App\controller;

use App\controller\common\PrimaryController;
use App\model\AccountModel;

/**
 * 处理用户登入登出行为
 *
 *
 *
 *
 */
class SecurityController extends PrimaryController
{
    protected $AccountModel;
    private $redirect = '/';
    public function __construct()
    {
        parent::__construct();
        $this->AccountModel = new AccountModel();
    }
    public function signinAction()
    {
        // 重定向
        if ($this->input->get('origin')) {
            $this->redirect = $this->input->get('origin');
        }
        if ($this->isSigned()) {
            $this->URL->redirect($this->redirect);
        }
        // echo password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        // if POST else GET
        if ($this->input->method('post')) {
            // 根据 user 字段查找用户
            if (!$account = $this->AccountModel->get($this->input->post('account'))) {
                return trigger_error('用户不存在');
            }
            // 验证密码
            if (!password_verify($this->input->post('password'), $account['password'])) {
                return trigger_error('密码错误');
            }
            // 更新登录时间
            $this->AccountModel->update($account['id'], array('logged'=>time()));
            // 生成会话
            $_SESSION['account'] = $account;
            $_SESSION['account']['last_operate'] = time();
            $this->URL->redirect($this->redirect);
        }
        $this->parser->display('security/signin');
    }
    public function signoutAction()
    {
        session_destroy();
        if (isset($_SESSION['account'])) {
            unset($_SESSION['account']);
        }
    }
    public function signupAction()
    {
        $this->parser->display('security/signup');
    }
}
