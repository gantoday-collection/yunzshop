<?php

namespace app\frontend\modules\payment\listeners;

use app\common\events\payment\GetOrderPaymentTypeEvent;

/**
 * Created by PhpStorm.
 * Author: 芸众商城 www.yunzshop.com
 * Date: 2017/5/17
 * Time: 下午5:44
 */
class Alipay
{
    public function onGetPaymentTypes(GetOrderPaymentTypeEvent $event)
    {
        if (\Setting::get('shop.pay.alipay')) {
            $result = [
                'name' => '支付宝支付',
                'value' => '2'
            ];
            $event->addData($result);

        }
        return null;
    }

    public function subscribe($events)
    {
        $events->listen(
            GetOrderPaymentTypeEvent::class,
            self::class . '@onGetPaymentTypes'
        );
    }
}