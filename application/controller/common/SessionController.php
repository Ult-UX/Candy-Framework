<?php
namespace App\controller\common;

use Candy\mvc\Controller;

/**
 * 自动处理所有会话，影响用户登录行为
 *
 *
 *
 *
 */
abstract class SessionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // 启用会话
        session_start();
        // 检查用户登入会话
        if (isset($_SESSION['user_signed'])) {
            // 检查用户登入会话是否过期，如过期将删除回话，否则更新已登入用户最后操作时间
            if (time() - $_SESSION['user_signed']['last_operate'] > $this->config::get('user_signed_session_life')) {
                unset($_SESSION['user_signed']);
            } else {
                $_SESSION['user_signed']['last_operate'] = time();
            }
        }
    }
}
