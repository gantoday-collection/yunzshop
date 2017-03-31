<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 2017/3/28
 * Time: 上午10:49
 */

namespace app\common\components;


use app\common\helpers\Client;
use app\common\helpers\Url;
use app\frontend\modules\member\services\MemberService;

class ApiController extends BaseController
{
    protected $publicAction = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function preAction()
    {
        parent::preAction();

        $this->setCookie();
        if (!MemberService::isLogged() && !in_array($this->action,$this->publicAction)) {
            $yz_redirect  = \YunShop::request()->yz_redirect;
            $type  = \YunShop::request()->type;

            redirect(Url::absoluteApi('member.login.index', ['type'=>$type,'yz_redirect'=>$yz_redirect]))->send();
        } else {
            $redirect_url = $this->_getClientRequestUrl();

            if ($redirect_url) {
                redirect($redirect_url . '?login&session_id=' . session_id())->send();
            }
        }
    }


    private function setCookie()
    {
        $session_id = '';
        if (isset(\YunShop::request()->state) && !empty(\YunShop::request()->state) && strpos(\YunShop::request()->state, 'yz-')) {
            $pieces = explode('-', \YunShop::request()->state);
            $session_id = $pieces[1];
            unset($pieces);
        }

        if (empty($session_id) && \YunShop::request()->session_id) {
            $session_id = \YunShop::request()->session_id;
        }

        if (empty($session_id)) {
            $session_id = $_COOKIE[session_name()];
        }
        if (empty($session_id)) {
            $session_id = \YunShop::app()->uniacid . '-' . Client::random(20) ;
            $session_id = md5($session_id);
            setcookie(session_name(), $session_id);
        }

        session_id($session_id);
        session_start();
    }
}