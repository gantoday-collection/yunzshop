<?php
/**
 * Created by PhpStorm.
 *
 * User: king/QQ：995265288
 * Date: 2018/6/11 上午10:27
 * Email: livsyitian@163.com
 */

namespace app\frontend\modules\withdraw\listeners;


use app\common\events\withdraw\WithdrawAppliedEvent;
use app\common\events\withdraw\WithdrawApplyEvent;
use app\common\events\withdraw\WithdrawApplyingEvent;
use app\common\exceptions\AppException;
use app\frontend\modules\withdraw\models\Income;
use app\frontend\modules\withdraw\services\PayWayValidatorService;
use app\frontend\modules\withdraw\services\OutlayService;
use app\frontend\modules\withdraw\services\DataValidatorService;
use app\Jobs\WithdrawFreeAuditJob;
use Illuminate\Contracts\Events\Dispatcher;

class WithdrawApplyListener
{
    public function subscribe(Dispatcher $dispatcher)
    {
        /**
         * 提现申请，验证打款方式
         */
        $dispatcher->listen(
            WithdrawApplyEvent::class,
            static::class . "@validatorPayWay",
            999
        );

        /**
         * 提现申请，验证相关数据
         */
        $dispatcher->listen(
            WithdrawApplyEvent::class,
            static::class . "@withdrawApply",
            998
        );


        /**
         * 提现申请中，更新收入数据
         */
        $dispatcher->listen(
            WithdrawApplyingEvent::class,
            static::class . "@withdrawApplying",
            999
        );


        /**
         * 提现申请后，免审核任务
         */
        $dispatcher->listen(
            WithdrawAppliedEvent::class,
            static::class . "@withdrawApplied",
            999
        );
    }


    /**
     * 提现申请，验证打款方式
     *
     * @param $event WithdrawApplyingEvent
     */
    public function validatorPayWay($event)
    {
        $withdrawModel = $event->getWithdrawModel();

        (new PayWayValidatorService())->validator($withdrawModel->pay_way);
    }


    /**
     * 提现申请，验证相关数据
     *
     * @param WithdrawApplyingEvent $event
     */
    public function withdrawApply($event)
    {
        $withdrawModel = $event->getWithdrawModel();

        $withdrawOutlayService = new OutlayService($withdrawModel);

        $withdrawModel->poundage_rate = $withdrawOutlayService->getPoundageRate();
        $withdrawModel->poundage = $withdrawOutlayService->getPoundage();
        $withdrawModel->servicetax_rate = $withdrawOutlayService->getServiceTaxRate();
        $withdrawModel->servicetax = $withdrawOutlayService->getServiceTax();

        if($withdrawModel->withdraw_set['balance_special'] == '1' && $withdrawModel->pay_way == 'balance') {
            $withdrawModel->poundage_rate = $withdrawOutlayService->getToBalancePoundageRate();
            $withdrawModel->poundage = $withdrawOutlayService->getToBalancePoundage();
            $withdrawModel->servicetax_rate = $withdrawOutlayService->getToBalanceServiceTaxRate();
            $withdrawModel->servicetax = $withdrawOutlayService->getToBalanceServiceTax();
        }

        (new DataValidatorService($withdrawModel))->validator();
    }




    /**
     * 提现申请中，更新收入数据
     *
     * @param $event WithdrawApplyingEvent
     * @throws AppException
     */
    public function withdrawApplying($event)
    {
        $withdrawModel = $event->getWithdrawModel();

        $income_ids = explode(',', $withdrawModel->type_id);

        $result = Income::uniacid()->whereIn('id', $income_ids)->update(['status' => Income::STATUS_WITHDRAW, 'pay_status' => Income::PAY_STATUS_INITIAL]);
        if ($result < 1) {
            throw new AppException("{$withdrawModel->type_name}收入记录更新失败");
        }
    }


    /**
     * 提现申请后，免审核任务
     *
     * @param $event WithdrawApplyingEvent
     * @throws AppException
     */
    public function withdrawApplied($event)
    {
        $withdrawModel = $event->getWithdrawModel();

        $withdraw_set = $withdrawModel->withdraw_set;
        if ($withdraw_set['free_audit'] == 1) {

            $free_audit = ['balance', 'wechat'];
            if (in_array($withdrawModel->pay_way, $free_audit)) {

                $job = new WithdrawFreeAuditJob($withdrawModel);
                dispatch($job);
            }
        }
    }
}