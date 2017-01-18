<?php
namespace App\controller\common;

use Candy\framework\Controller;
use App\model\OptionModel;

/**
 * 站点初始化引导
 *
 */
abstract class BootstrapController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();

        var_dump($this->URL->build());
        var_dump($this->URL->build('test'));

        $_SESSION['visitor'] = array(

        );
        // var_dump($_SERVER['HTTP_USER_AGENT'],$this->input->getUserAgent());
        // var_dump($this->input->getClientIP());
        // var_dump($this->isSigned());
        if ($this->isSigned()) {
            if ($_SERVER['REQUEST_TIME'] - $_SESSION['account']['last_operate'] > $this->config->get('account_session_life')) {
                // $this->URL->redirect('signout', true);
            }
            $_SESSION['account']['last_operate'] = time();
        }
    }
    public function isSigned()
    {
        if (isset($_SESSION['account'])) {
            return $_SESSION['account'];
        }
    }
}
