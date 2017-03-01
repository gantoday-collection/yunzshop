<?php
/**
 * Created by PhpStorm.
 * User: dingran
 * Date: 17/2/23
 * Time: 上午11:20
 */

namespace app\frontend\modules\member\services;

use app\frontend\modules\member\services\MemberMcService;
use app\frontend\modules\member\models\MemberAppWechatModel;

class MemberAppWechatService extends MemberMcService
{
    private $_login_type    = 3;

    public function __construct()
    {}

    public function login()
    {
        $uniacid      = \YunShop::app()->uniacid;

        $appId        = \YunShop::app()->account['key'];
        $appSecret    = \YunShop::app()->account['secret'];
        $code         = \YunShop::request()->code;

        $tokenurl = $this->_getTokenUrl($appId, $appSecret, $code);

        if ($this->isLogged()) {
            show_json(1, array('member_id'=> $_SESSION['member_id']));
        }

        if (!empty($code)) {
            $resp     = ihttp_get($tokenurl);
            $token    = @json_decode($resp['content'], true);

            if (!empty($token) && is_array($token) && $token['errmsg'] == 'invalid code') {
                show_json(0, array('msg'=>'请求错误'));
            }

            $userinfo_url = $this->_getUserInfoUrl($token['accesstoken'], $token['openid']);
            $userinfo = ihttp_get($userinfo_url);

            if (is_array($userinfo) && !empty($userinfo['unionid'])) {
                $UnionidInfo = MemberUniqueModel::getUnionidInfo($uniacid, $userinfo['unionid']);

                $types = expload($UnionidInfo['type'], '|');

                if ($UnionidInfo['unionid']) {
                    if (!in_array($this->_login_type, $types)) {
                        //更新ims_yz_member_unique表
                        MemberUniqueModel::updateData(array(
                            'unque_id'=>$UnionidInfo['unque_id'],
                            'type' => $UnionidInfo['type'] . '|' . $this->_login_type
                        ));
                    }

                    $_SESSION['member_id'] = $UnionidInfo['member_id'];
                } else {
                    $member_id = McMappingFansModel::getUId($uniacid, $token['openid']);
                    //添加ims_yz_member_unique表
                    MemberUniqueModel::insertData(array(
                        'uniacid' => $uniacid,
                        'unionid' => $token['unionid'],
                        'member_id' => $member_id,
                        'type' => $this->_login_type
                    ));

                    $_SESSION['member_id'] = $member_id;
                }
            } else {
                show_json(0, array('msg'=> '请求错误'));
            }
        } else {
            show_json(0, array('msg'=> '请求错误'));
        }

        show_json(1, array('member_id', $_SESSION['member_id']));
    }

    public function isLogged()
    {
        return !empty($_SESSION['member_id']);
    }

    private function _getTokenUrl($appId, $appSecret, $code)
    {
        return "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appId}&secret={$appSecret}&code={$code}&grant_type=authorization_code";
    }

    private function _getUserInfoUrl($accesstoken, $openid)
    {
        return "https://api.weixin.qq.com/sns/userinfo?access_token={$accesstoken}&openid={$openid}&lang=zh_CN";
    }
}