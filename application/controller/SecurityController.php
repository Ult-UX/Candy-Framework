<?php
namespace App\controller;

use App\controller\common\PrimaryController;
use App\model\UserModel;

/**
 * 处理用户登入登出行为
 *
 *
 *
 *
 */
class SecurityController extends PrimaryController
{
    protected $UserModel;
    public function __construct()
    {
        parent::__construct();
        $this->UserModel = new UserModel();
    }
    public function signinAction()
    {
        if (isset($_SESSION['user_signed'])) {
            // 重定向
            return;
        }
        // echo password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        // if POST else GET
        if ($this->input->method('post')) {
            // 根据 user 字段查找用户
            if (!$user = $this->UserModel->get($this->input->post('user'))) {
                return trigger_error('用户不存在');
            }
            // 验证密码
            if (!password_verify($this->input->post('password'), $user['password'])) {
                return trigger_error('密码错误');
            }
            // 更新登录时间
            $this->UserModel->update($user['uid'], array('logged'=>time()));
            // 生成会话
            $_SESSION['user_signed'] = $user;
            $_SESSION['user_signed']['last_operate'] = time();
            // 重定向
        }
        $this->parser->display('security/signin');
    }
    public function signoutAction()
    {
        if (isset($_SESSION['user_signed'])) {
            unset($_SESSION['user_signed']);
        }
    }
    public function signupAction()
    {
        $this->parser->display('security/signup');
    }
}
