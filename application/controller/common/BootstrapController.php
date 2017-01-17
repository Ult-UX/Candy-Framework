<?php
namespace App\controller\common;

use Candy\mvc\Controller;
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
        $_SESSION['visitor'] = array(

        );
        // var_dump($_SERVER['HTTP_USER_AGENT'],$this->input->getUserAgent());
        // var_dump($this->input->getClientIP());
    }
}
