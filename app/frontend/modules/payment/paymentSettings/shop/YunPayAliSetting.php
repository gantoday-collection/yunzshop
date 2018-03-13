<?php
/**
 * Author: 芸众商城 www.yunzshop.com
 * Date: 2018/3/6
 * Time: 下午4:22
 */

namespace app\frontend\modules\payment\paymentSettings\shop;


class YunPayAliSetting extends BaseSetting
{
    public function canUse()
    {
        $set = \Setting::get('plugin.yun_pay_set');

        return !is_null($set) && 1 == $set['switch'];
    }

    public function exist()
    {
        return \Setting::get('plugin.yun_pay_set') !== null;
    }
}