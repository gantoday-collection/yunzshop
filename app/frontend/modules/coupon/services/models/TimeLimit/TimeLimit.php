<?php
/**
 * Created by PhpStorm.
 * Author: 芸众商城 www.yunzshop.com
 * Date: 2017/3/29
 * Time: 下午5:23
 */

namespace app\frontend\modules\coupon\services\models\TimeLimit;


use app\frontend\modules\coupon\services\models\Coupon;
use app\frontend\modules\orderGoods\models\PreGeneratedOrderGoodsGroup;
use app\frontend\modules\order\models\PreGeneratedOrder;

abstract class TimeLimit
{
    /**
     * 优惠券数据库model
     * @var \app\common\models\Coupon
     */
    protected $dbCoupon;
    /**
     * @var Coupon
     */
    protected $coupon;
    /**
     * @var PreGeneratedOrder
     */
    protected $orderModel;
    /**
     * @var PreGeneratedOrderGoodsGroup
     */
    protected $orderGoodsModelGroup;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
        $this->dbCoupon = $coupon->getMemberCoupon()->belongsToCoupon;
        $this->orderModel = $coupon->getPreGeneratedOrder();

    }
    abstract public function valid();
}