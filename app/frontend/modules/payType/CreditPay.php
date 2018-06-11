<?php
/**
 * Created by PhpStorm.
 * User: shenyang
 * Date: 2018/6/7
 * Time: 下午4:53
 */

namespace app\frontend\modules\orderPay\payType;

use app\frontend\modules\finance\models\Balance;

class CreditPay extends BasePayType
{
    /**
     * @param array $option
     * @return array
     * @throws \app\common\exceptions\AppException
     */
    function getPayParams($option)
    {
        $result = [
            'member_id' => $this->orderPay->orders->first()->uid,
            'operator' => Balance::OPERATOR_ORDER_,//订单
            'operator_id' => $this->orderPay->id,
            'remark' => '合并支付(id:' . $this->orderPay->id . '),余额付款' . $this->orderPay->amount . '元',
            'service_type' => Balance::BALANCE_CONSUME,
            'trade_no' => 0,
        ];

        return array_merge(parent::getPayParams($option), $result);
    }
}