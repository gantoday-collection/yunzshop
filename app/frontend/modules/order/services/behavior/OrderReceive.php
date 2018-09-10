<?php
/**
 * Created by PhpStorm.
 * Author: 芸众商城 www.yunzshop.com
 * Date: 2017/2/28
 * Time: 上午10:57
 * comment:订单收货类
 */

namespace app\frontend\modules\order\services\behavior;

use app\common\exceptions\ShopException;
use app\common\models\Order;

class OrderReceive extends ChangeStatusOperation
{
    protected $statusBeforeChange = [ORDER::WAIT_RECEIVE];
    protected $statusAfterChanged = ORDER::COMPLETE;
    protected $name = '收货';
    protected $time_field = 'finish_time';
    protected $past_tense_class_name = 'OrderReceived';
}