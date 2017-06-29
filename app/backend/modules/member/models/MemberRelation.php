<?php
/**
 * Created by PhpStorm.
 * Author: 芸众商城 www.yunzshop.com
 * Date: 17/3/8
 * Time: 上午9:32
 */

namespace app\backend\modules\member\models;

use app\backend\models\BackendModel;
use app\backend\modules\order\models\Order;
use app\common\events\member\MemberRelationEvent;
use app\common\services\MessageService;
use app\frontend\modules\member\models\SubMemberModel;

class MemberRelation extends BackendModel
{
    public $table = 'yz_member_relation';

    public $timestamps = false;

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    public $fillable = ['uniacid', 'status', 'become', 'become_order', 'become_child', 'become_ordercount',
        'become_moneycount', 'become_goods_id', 'become_info', 'become_check'];

    /**
     * 不可批量赋值的属性
     *
     * @var array
     */
    public $guarded = [];

    /**
     * 获取会员关系链数据
     *
     * @return mixed
     */
    public static function getSetInfo()
    {
        return self::uniacid();
    }

    /**
     * 用户是否达到发展下线条件
     *
     * @return bool
     */
    public static function checkAgent($uid)
    {
        $info = self::getSetInfo()->first();

        if (empty($info)) {
            return [];
        }

        $member_info = SubMemberModel::getMemberShopInfo($uid);

        if (!empty($member_info)) {
            $data = $member_info->toArray();
        }

        if ($data['is_agent'] == 0) {
            switch ($info['become']) {
                case 0:
                    $isAgent = true;
                    break;
                case 2:
                    $cost_num = Order::getCostTotalNum($uid);

                    if ($cost_num >= $info['become_ordercount']) {
                        $isAgent = true;
                    }
                    break;
                case 3:
                    $cost_price = Order::getCostTotalPrice($uid);

                    if ($cost_price >= $info['become_moneycount']) {
                        $isAgent = true;
                    }
                    break;
                case 4:
                    $isAgent = self::checkOrderGoods($info['become_goods_id'], $uid);
                    break;
                default:
                    $isAgent = false;
            }
        }

        if ($isAgent) {
            if ($info['become_check'] == 0) {
                $member_info->is_agent = 1;
                $member_info->status = 2;

                $member_info->save();
            }
        }
    }

    /**
     * 设置用户关系链
     *
     * @return void
     */
    public function setAgent()
    {
        $info = self::getSetInfo()->first()->toArray();

        $member_info = SubMemberModel::getMemberShopInfo(\YunShop::app()->getMemberId())->first();

        if (!empty($member_info)) {
            $data = $member_info->toArray();
        }

        $isAgent = false;
        if ($info['status'] == 1 && $data['is_agent'] == 0) {
            $mid = \YunShop::request()->mid ? \YunShop::request()->mid : 0;
            if ($mid != 0 && $data['member_id'] != $mid) {
                $member_info->parent_id = $mid;
                $member_info->save();
            }
        }
    }

    /**
     * 检查用户订单中是否包含指定商品
     *
     * @param $goods_id
     * @return bool
     */
    public static function checkOrderGoods($goods_id, $uid)
    {
        $list = \app\common\models\Order::getOrderListByUid($uid);

        if (!empty($list)) {
            $list = $list->toArray();

            foreach ($list as $rows) {
                foreach ($rows['has_many_order_goods'] as $item) {
                    if ($item['goods_id'] == $goods_id) {
                        \Log::debug('购买商品指定商品', [$goods_id]);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * 获取成为下线条件
     *
     * @return int
     */
    public function getChildAgentInfo()
    {
        $info = self::getSetInfo()->first();

        if (!empty($info)) {

            return $info->become_child;
        }
    }

    /**
     * 成为下线
     *
     * @param $mid
     * @param MemberShopInfo $model
     */
    private function changeChildAgent($mid, MemberShopInfo $model)
    {
        $member_info = SubMemberModel::getMemberShopInfo($mid);

        if ($member_info && $member_info->is_agent) {
            $model->parent_id = $mid;
            $model->child_time = time();

            if ($model->save()) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * 检查是否能成为下线
     *
     * 首次点击分享连接 / 无条件发展下线权利
     *
     * 触发 入口
     *
     * @param $mid
     * @param MemberShopInfo $user
     */
    public function becomeChildAgent($mid, \app\common\models\MemberShopInfo $model)
    {
        $set = self::getSetInfo()->first();

        if (empty($set)) {
            return;
        }

        $member = SubMemberModel::getMemberShopInfo($model->member_id);

        if (empty($member)) {
            return;
        }

        if ($member->is_agent == 1) {
            return;
        }

        $parent = null;

        if (!empty($mid)) {
            $parent =  SubMemberModel::getMemberShopInfo($mid);
        } else {
            if (empty($member->inviter)) {
                $model->parent_id = intval($mid);
                $model->child_time = time();

                if (empty($become_child)) {
                    $model->inviter = 1;
                } else {
                    $model->inviter = 0;
                }

                $model->save();
            }
        }

        $parent_is_agent = !empty($parent) && $parent->is_agent == 1 && $parent->status == 2;

        $become_child =  intval($set->become_child);
        $become_check = intval($set->become_check);

        if ($parent_is_agent && empty($member->inviter)) {
            if ($member->member_id != $parent->member_id) {
                $this->changeChildAgent($mid, $model);

                if (empty($become_child)) {
                    $model->inviter = 1;

                    //notice
                    self::sendAgentNotify($member->member_id, $mid);
                } else {
                    $model->inviter = 0;
                }

                $model->save();
            }
        }

        if (empty($set->become) ) {
            $member->is_agent = 1;

            if ($become_check == 0) {
                $member->status = 2;
                $member->agent_time = time();

                if ($member->inviter == 0) {
                    $member->inviter = 1;
                    $member->parent_id = 0;
                }
            } else {
                $member->status = 1;
            }

            if ($member->save()) {
                self::setRelationInfo($member);
            }
        }
    }

    /**
     * 成为下线条件 首次下单
     *
     * 触发 确认订单
     *
     * @return void
     */
    public static function checkOrderConfirm($uid)
    {
        $set = self::getSetInfo()->first();

        if (empty($set)) {
            return;
        }

        $member = SubMemberModel::getMemberShopInfo($uid);

        if (empty($member)) {
            return;
        }

        $become_child = intval($set->become_child);

        if ($member->parent_id == 0) {
            if ($become_child == 1 && empty($member->inviter)) {
                $member->child_time = time();
                $member->inviter = 1;

                $member->save();
            }
        } else {
            $parent = SubMemberModel::getMemberShopInfo($member->parent_id);

            $parent_is_agent = !empty($parent) && $parent->is_agent == 1 && $parent->status == 2;

            if ($parent_is_agent) {
                if ($become_child == 1) {
                    if (empty($member->inviter) && $member->member_id != $parent->member_id) {
                        $member->parent_id = $parent->member_id;
                        $member->child_time = time();
                        $member->inviter = 1;

                        $member->save();

                        //message notice
                        self::sendAgentNotify($member->member_id, $parent->member_id);
                    }
                }
            }
        }
    }

    /**
     * 发展下线资格 付款后
     *
     * 成为下线条件 首次付款
     *
     * 触发 支付回调
     *
     * @return void
     */
    public static function checkOrderPay($uid)
    {
        $set = self::getSetInfo()->first();
        \Log::debug('付款后');
        if (empty($set)) {
            return;
        }

        $member = SubMemberModel::getMemberShopInfo($uid);
        if (empty($member)) {
            return;
        }

        $become_child = intval($set->become_child);

        $parent = SubMemberModel::getMemberShopInfo($member->parent_id);

        $parent_is_agent = !empty($parent) && $parent->is_agent == 1 && $parent->status == 2;

        //成为下线
        if ($member->parent_id == 0) {
            if ($become_child == 2 && empty($member->inviter)) {
                $member->child_time = time();
                $member->inviter = 1;

                $member->save();
            }
        } else {
            if ($parent_is_agent) {
                if ($become_child == 2) {
                    if (empty($member->inviter) && $member->member_id != $parent->member_id) {
                        $member->parent_id = $parent->member_id;
                        $member->child_time = time();
                        $member->inviter = 1;

                        $member->save();

                        //message notice
                        self::sendAgentNotify($member->member_id, $parent->member_id);
                    }
                }
            }
        }

        //发展下线资格
        $isagent = $member->is_agent == 1 && $member->status == 2;

        if (!$isagent && empty($set->become_order)) {
            if (intval($set->become) == 4 && !empty($set->become_goods_id)) {
                $result = self::checkOrderGoods($set->become_goods_id, $uid);

                if ($result) {
                    $member->status = 2;
                    $member->is_agent = 1;
                    $member->agent_time = time();

                    if ($member->inviter == 0) {
                        $member->inviter = 1;
                        $member->parent_id = 0;
                    }

                    if ($member->save()) {
                        self::setRelationInfo($member);
                    }
                }
            }

            if ($set->become == 2 || $set->become == 3) {
                $parentisagent = true;

                if (!empty($member->parent_id)) {
                    $parent = SubMemberModel::getMemberShopInfo($member->parent_id);
                    if (empty($parent) || $parent->is_agent != 1 || $parent->status != 2) {
                        $parentisagent = false;
                    }
                }

                if ($parentisagent) {
                    $can = false;

                    if ($set->become == '2') {
                        $ordercount = Order::getCostTotalNum($member->member_id);
                        \Log::debug('用户：'. $ordercount);
                        \Log::debug('系统：'. intval($set->become_ordercount));
                        $can = $ordercount >= intval($set->become_ordercount);
                    } else if ($set->become == '3') {
                        $moneycount = Order::getCostTotalPrice($member->member_id);

                        $can = $moneycount >= floatval($set->become_moneycount);
                    }

                    if ($can) {
                        $become_check = intval($set->become_check);

                        $member->is_agent = 1;

                        if ($become_check == 0) {
                            $member->status = 2;
                            $member->agent_time = time();

                            if ($member->inviter == 0) {
                                $member->inviter = 1;
                                $member->parent_id = 0;
                            }
                        } else {
                            $member->status = 1;
                        }

                        if ($member->save()) {
                            self::setRelationInfo($member);
                        }
                    }
                }
            }
        }
    }

    /**
     * 发现下线资格 完成后
     *
     * 触发 订单完成
     *
     * @return void
     */
    public static function checkOrderFinish($uid)
    {
        $set = self::getSetInfo()->first();

        \Log::debug('订单完成');

        if (empty($set)) {
            return;
        }
        \Log::debug('关系链设置');
        $member = SubMemberModel::getMemberShopInfo($uid);

        if (empty($member)) {
            return;
        }

        $isagent = $member->is_agent == 1 && $member->status == 2;

        if (!$isagent && $set->become_order == 1) {
            //购买指定商品
            if (intval($set->become) == 4 && !empty($set->become_goods_id)) {
                $result = self::checkOrderGoods($set->become_goods_id, $uid);

                if ($result) {
                    $member->status = 2;
                    $member->is_agent = 1;
                    $member->agent_time = time();

                    if ($member->inviter == 0) {
                        $member->inviter = 1;
                        $member->parent_id = 0;
                    }

                    if ($member->save()) {
                        self::setRelationInfo($member);
                    }
                }
            }

            \Log::debug('条件完成后');
            //消费
            if ($set->become == 2 || $set->become == 3) {
                $parentisagent = true;

                if (!empty($member->parent_id)) {
                    $parent = SubMemberModel::getMemberShopInfo($member->parent_id);
                    if (empty($parent) || $parent->is_agent != 1 || $parent->status != 2) {
                        $parentisagent = false;
                    }
                }

                if ($parentisagent) {
                    $can = false;

                    if ($set->become == '2') {
                        $ordercount = Order::getCostTotalNum($member->member_id);
                        \Log::debug('系统：' . intval($set->become_ordercount));
                        \Log::debug('会员：' . $ordercount);
                        $can = $ordercount >= intval($set->become_ordercount);
                    } else if ($set->become == '3') {
                        $moneycount = Order::getCostTotalPrice($member->member_id);

                        $can = $moneycount >= floatval($set->become_moneycount);
                    }

                    if ($can) {
                        $become_check = intval($set->become_check);

                        $member->is_agent = 1;

                        if ($become_check == 0) {
                            $member->status = 2;
                            $member->agent_time = time();

                            if ($member->inviter == 0) {
                                $member->inviter = 1;
                                $member->parent_id = 0;
                            }
                        } else {
                            $member->status = 1;
                        }

                        if ($member->save()) {
                            self::setRelationInfo($member);
                        }
                    }
                }
            }
        }
    }

    /**
     * 获得推广权限通知
     *
     * @param $uid
     */
    public static function sendGeneralizeNotify($uid)
    {
        \Log::debug('获得推广权限通知');

        $member = Member::getMemberByUid($uid)->with('hasOneFans')->first();

        event(new MemberRelationEvent($member));

        $member->follow = $member->hasOneFans->follow;
        $member->openid = $member->hasOneFans->openid;

        $uniacid = \YunShop::app()->uniacid ?: $member->uniacid;

        self::generalizeMessage($member, $uniacid);
    }

    public static function generalizeMessage($member, $uniacid)
    {
        $msg_set = \Setting::get('relation_base');
        if ($msg_set['template_id'] && ($member->follow == 1)) {
            $message = $msg_set['generalize_msg'];
            $message = str_replace('[昵称]', $member->nickname, $message);
            $message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
            $msg = [
                "first" => '您好',
                "keyword1" => "获得推广权限通知",
                "keyword2" => $message,
                "remark" => "",
            ];

            if ($msg_set['template_id']) {
                MessageService::notice($msg_set['template_id'], $msg, $member->openid, $uniacid);
            }
        }
    }

    /**
     * 新增下线通知
     *
     * @param $uid
     */
    public static function sendAgentNotify($uid, $puid)
    {
        \Log::debug('新增下线通知');
        $parent = Member::getMemberByUid($puid)->with('hasOneFans')->first();
        $parent->follow = $parent->hasOneFans->follow;
        $parent->openid = $parent->hasOneFans->openid;

        $member = Member::getMemberByUid($uid)->first();

        $uniacid = \YunShop::app()->uniacid ?: $parent->uniacid;

        self::agentMessage($parent, $member, $uniacid);
    }

    public static function agentMessage($parent, $member, $uniacid)
    {
        $msg_set = \Setting::get('relation_base');
        if ($msg_set['template_id'] && ($parent->follow == 1)) {
            $message = $msg_set['agent_msg'];
            $message = str_replace('[昵称]', $parent->nickname, $message);
            $message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
            $message = str_replace('[下级昵称]', $member->nickname, $message);
            $msg = [
                "first" => '您好',
                "keyword1" => "新增下线通知",
                "keyword2" => $message,
                "remark" => "",
            ];

            if ($msg_set['template_id']) {
                MessageService::notice($msg_set['template_id'], $msg, $parent->openid, $uniacid);
            }
        }
    }

    private static function setRelationInfo($member)
    {
        if ($member->is_agent == 1 && $member->status == 2) {
            Member::setMemberRelation($member->member_id,$member->parent_id);

            //message notice
            self::sendGeneralizeNotify($member->member_id);
        }
    }
}