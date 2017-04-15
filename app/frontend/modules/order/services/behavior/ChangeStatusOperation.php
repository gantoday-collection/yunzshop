<?php
/**
 * Created by PhpStorm.
 * User: shenyang
 * Date: 2017/3/20
 * Time: 下午8:16
 */

namespace app\frontend\modules\order\services\behavior;


abstract class ChangeStatusOperation extends OrderOperation
{
    /**
     * @var改变后状态
     */
    protected $statusAfterChanged;
    /**
     * 更新订单表
     * @return bool
     */
    protected function updateTable(){
        $this->order->status = $this->statusAfterChanged;
        if(isset($this->time_field)){
            $time_fields = $this->time_field;
            $this->order->$time_fields = time();
        }
        return $this->order->save();
    }

    /**
     * 执行订单操作
     * @return mixed
     */
    public function execute()
    {
        $result = $this->updateTable();
        $this->_fireEvent();
        return $result;
    }
}