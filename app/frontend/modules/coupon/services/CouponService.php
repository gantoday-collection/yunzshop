<?php

namespace app\frontend\modules\coupon\services;

use app\common\helpers\ArrayHelper;
use app\common\models\goods\GoodsCoupon;
use app\frontend\modules\coupon\services\models\Coupon;
use app\frontend\modules\order\models\PreGeneratedOrder;
use app\Jobs\addGoodsCouponQueueJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;

class CouponService
{
    use DispatchesJobs;
    private $order;
    private $orderGoods;
    private $coupon_method = null;

    public function __construct( $order, $coupon_method = null, $orderGoods = [])
    {

        $this->order = $order;
        $this->orderGoods = $orderGoods;
        $this->coupon_method = $coupon_method;
    }

    /**
     * 获取订单优惠金额
     * @return int
     */
    public function getOrderDiscountPrice()
    {
        return $this->getAllValidCoupons()->sum(function ($coupon) {
            /**
             * @var $coupon Coupon
             */
            //$coupon->activate();
            return $coupon->getDiscountAmount();
        });
    }

    /**
     * 激活订单优惠券
     */
    public function activate()
    {
        return $this->getAllValidCoupons()->each(function ($coupon) {
            /**
             * @var $coupon Coupon
             */
            $coupon->activate();
        });
    }

    /**
     * 获取订单可算的优惠券
     * @return Collection
     */
    public function getOptionalCoupons()
    {
        //dd(MemberCouponService::getCurrentMemberCouponCache($this->order->belongsToMember));
        //dd($this->getMemberCoupon());
        $coupons = $this->getMemberCoupon()->map(function ($memberCoupon) {
            return new Coupon($memberCoupon, $this->order);
        });
        $result = $coupons->filter(function ($coupon) {
            /**
             * @var $coupon Coupon
             */
            //不可选
            if (!$coupon->isOptional()) {
                return false;
            }
            $coupon->getMemberCoupon()->valid = $coupon->isChecked() || $coupon->valid();//界面标蓝
            $coupon->getMemberCoupon()->checked = $coupon->isChecked();//界面选中

            return true;
        });

        return $result;
    }

    /**
     * 记录使用过的优惠券
     */
    public function destroyUsedCoupons()
    {
        $this->getSelectedMemberCoupon()->map(function ($memberCoupon) {
            return (new Coupon($memberCoupon, $this->order))->destroy();
        });
    }

    /**
     * 获取所有选中并有效的优惠券
     * @return Collection
     */
    public function getAllValidCoupons()
    {
        $coupon = $this->getSelectedMemberCoupon()->map(function ($memberCoupon) {
            return new Coupon($memberCoupon, $this->order);
        });
        $result = $coupon->filter(function ($coupon) {
            /**
             * @var $coupon Coupon
             */
            return $coupon->valid();
        });

        return $result;
    }

    /**
     * 用户拥有的优惠券
     * @return Collection
     */
    private function getMemberCoupon()
    {
        $coupon_method = $this->coupon_method;
        $result = MemberCouponService::getCurrentMemberCouponCache($this->order->belongsToMember);
        if (isset($coupon_method)) {// 折扣/立减
            $result = $result->filter(function ($memberCoupon) use ($coupon_method) {
                return $memberCoupon->belongsToCoupon->coupon_method == $coupon_method;
            });
        }
        //dd($result->toArray());exit;
        return $result;

    }

    /**
     * 用户拥有并选中的优惠券
     * @return Collection
     */
    private function getSelectedMemberCoupon()
    {
        $member_coupon_ids = ArrayHelper::unreliableDataToArray(\Request::input('member_coupon_ids'));

        return $this->getMemberCoupon()->filter(function ($memberCoupon) use ($member_coupon_ids) {
            return in_array($memberCoupon->id, $member_coupon_ids);
        });
    }

    public function sendCoupun()
    {
        $orderGoods = $this->orderGoods;
        foreach ($orderGoods as $goods) {
            $goodsCoupon = GoodsCoupon::ofGoodsId($goods->goods_id)->first();
            //未开启 或 已关闭
            if(!$goodsCoupon || !$goodsCoupon->is_coupon){
                continue;
            }
            //未设置优惠券 或 未设置赠送月份
            if(!$goodsCoupon->coupon_id || !$goodsCoupon->send_num){
                continue;
            }
            for ($i = 1; $i <= $goods->total; $i++) {
                $this->addSendCoupunQueue($goodsCoupon);
            }
        }
    }

    public function addSendCoupunQueue($goodsCoupon)
    {
        $queueData = [
            'uniacid' => \YunShop::app()->uniacid,
            'goods_id' => $goodsCoupon->goods_id,
            'uid' => $this->order->uid,
            'coupon_id' => $goodsCoupon->coupon_id,
            'send_num' => $goodsCoupon->send_num,
            'end_send_num' => 0,
            'status' => 0,
            'created_at' => time()
        ];
        $this->dispatch((new addGoodsCouponQueueJob($queueData)));
    }

}