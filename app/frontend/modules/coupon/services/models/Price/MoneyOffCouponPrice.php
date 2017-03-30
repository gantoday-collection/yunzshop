<?php
/**
 * 立减优惠券
 * Created by PhpStorm.
 * User: shenyang
 * Date: 2017/3/25
 * Time: 下午5:21
 */

namespace app\frontend\modules\coupon\services\models\Price;

use app\frontend\modules\goods\services\models\PreGeneratedOrderGoodsModel;

class MoneyOffCouponPrice extends CouponPrice
{
    public function valid()
    {

        //优惠券商品价格不小于订单满减价格
        if (!float_lesser($this->getOrderGoodsGroupPrice(), $this->dbCoupon->enough)) {

            return true;
        }
        return false;
    }
    private function getOrderGoodsGroupPrice(){
        return $this->coupon->getOrderGoodsInScope()->getVipPrice()-$this->coupon->getOrderGoodsInScope()->getCouponDiscountPrice();
    }
    private function getOrderGoodsPrice($orderGoods){
        return $orderGoods->getVipPrice() - $orderGoods->coupon_discount_price;
    }
    public function getPrice()
    {
        return $this->dbCoupon->deduct;
    }
    /**
     * 分配优惠金额 立减折扣券使用 商品折扣后价格计算
     */
    public function setOrderGoodsDiscountPrice()
    {
       // var_dump(debug_backtrace(0,9));

        //dd($this->getOrderGoodsInScope());
        foreach ($this->coupon->getOrderGoodsInScope()->getOrderGoodsGroup() as $orderGoods) {
            /**
             * @var $orderGoods PreGeneratedOrderGoodsModel
             */
            //(优惠券金额/折扣优惠券后价格)*折扣优惠券后价格
            $orderGoods->coupon_money_off_price += number_format(($this->getPrice() / $this->getOrderGoodsGroupPrice()) * $this->getOrderGoodsPrice($orderGoods), 2);

        }
    }
}