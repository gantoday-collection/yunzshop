<?php

/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 2017/3/27
 * Time: 下午10:15
 */

namespace app\frontend\modules\finance\controllers;

use app\common\components\BaseController;
use app\common\models\Income;
use app\frontend\modules\finance\models\IncomeWithdraw;

class IncomeController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIncomeCount()
    {
        $status = \YunShop::request()->status;
        $incomeModel = Income::getIncomes()->where('member_id', \YunShop::app()->getMemberId())->get();
        if ($status >= '0') {
            $incomeModel = $incomeModel->where('status', $status);
        }
        $config = \Config::get('income');
        $incomeData['total'] = [
            'title' => '推广收入',
            'type' => 'total',
            'type_name' => '推广佣金',
            'income' => $incomeModel->sum('amount')
        ];

        foreach ($config as $key => $item) {
            $typeModel = $incomeModel->where('type', $key);
            $incomeData[$key] = [
                'title' => $item['title'],
                'ico' => $item['ico'],
                'type' => $item['type'],
                'type_name' => $item['type_name'],
                'income' => $typeModel->sum('amount')
            ];
        }
        if ($incomeData) {
            return $this->successJson('获取数据成功!', $incomeData);
        }
        return $this->errorJson('未检测到数据!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIncomeList()
    {
        $incomeModel = Income::getIncomeInMonth()->where('member_id', \YunShop::app()->getMemberId())->get();
        if ($incomeModel) {
            return $this->successJson('获取数据成功!', $incomeModel);
        }
        return $this->errorJson('未检测到数据!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getDetail()
    {
        $id = \YunShop::request()->id;
        $detailModel = Income::getDetailById($id);
        if ($detailModel) {
            return '{"result":1,"msg":"成功","data":' . $detailModel->first()->detail . '}';
        }
        return $this->errorJson('未检测到数据!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchType()
    {
        $configs = \Config::get('income');
        foreach ($configs as $key => $config) {
            $searchType[$key]['title'] = $config['type_name'];
            $searchType[$key]['type'] = $config['type'];
        }
        if ($searchType) {
            return $this->successJson('获取数据成功!', $searchType);
        }
        return $this->errorJson('未检测到数据!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWithdraw()
    {
        $config = \Config::get('income');
        $incomeModel = Income::getIncomes()->where('member_id', \YunShop::app()->getMemberId());
        $incomeModel = $incomeModel->where('status', '0');
        if ($incomeModel->get()) {
            return $this->errorJson('未检测到可提现数据!'.\YunShop::app()->getMemberId());
        }

        foreach ($config as $key => $item) {
            $set[$key] = \Setting::get('income.withdraw.' . $key, ['roll_out_limit' => '100', 'poundage_rate' => '5']);
            $incomeModel = $incomeModel->where('type', $key);
            $amount = $incomeModel->sum('amount');

            if (isset($set[$key]['roll_out_limit']) && bccomp($amount, $set[$key]['roll_out_limit'], 2) != -1) {
                $type_id = '';
                foreach ($incomeModel->get() as $ids) {
                    $type_id .= $ids->type_id . ",";
                }
                $incomeData[] = [
                    'type' => $item['type'],
                    'type_name' => $item['type_name'],
                    'type_id' => rtrim($type_id, ','),
                    'income' => $incomeModel->sum('amount'),
                    'poundage' => $incomeModel->sum('amount') / 100 * $set[$key]['poundage_rate'],
                    'poundage_rate' => $set[$key]['poundage_rate'],
                    'can' => true,
                ];
            } else {
                $incomeData[] = [
                    'type' => $item['type'],
                    'type_name' => $item['type_name'],
                    'type_id' => '',
                    'income' => $incomeModel->sum('amount'),
                    'poundage' => $incomeModel->sum('amount') / 100 * $set[$key]['poundage_rate'],
                    'poundage_rate' => $set[$key]['poundage_rate'],
                    'can' => false,
                ];
            }
        }
        if ($incomeData) {
            return $this->successJson('获取数据成功!', $incomeData);
        }
        return $this->errorJson('未检测到数据!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveWithdraw()
    {
        $config = \Config::get('income');

        $withdrawData = \YunShop::request()->data;

        if (!$withdrawData) {
            return $this->errorJson('未检测到数据!');
        }

        $withdrawTotal = $withdrawData['total'];
        unset($withdrawData['total']);

        $incomeModel = Income::getIncomes();
        $incomeModel = $incomeModel->where('member_id', \YunShop::app()->getMemberId());
        $incomeModel = $incomeModel->where('status', '0');
        /**
         * 验证数据
         */
        foreach ($withdrawData as $key => $item) {

            $set[$key] = \Setting::get('income.withdraw.' . $key,
                [
                    'roll_out_limit' => '100',
                    'poundage_rate' => '5'
                ]
            );
            $incomeModel = $incomeModel->whereIn('type_id', [$item['type_id']]);
            $incomes = $incomeModel->get();

            if (isset($set[$key]['roll_out_limit']) &&
                bccomp($incomes->sum('amount'), $set[$key]['roll_out_limit'], 2) == -1
            ) {
                return $this->errorJson('提现失败,' . $item['type_name'] . '未达到提现标准!');
            }


        }

        $request = static::setWithdraw($withdrawData, $withdrawTotal);
        if ($request) {
            static::setIncomeAndOrder();
            return $this->successJson('提现成功!');
        }
        return $this->errorJson('提现失败!');
    }

    public function setIncomeAndOrder()
    {

    }

    /**
     * @param $withdrawData
     * @param $withdrawTotal
     * @return mixed
     */
    public function setWithdraw($withdrawData, $withdrawTotal)
    {
        foreach ($withdrawData as $item) {
            $data[] = [
                'uniacid' => \YunShop::app()->uniacid,
                'member_id' => \YunShop::app()->getMemberId(),
                'type' => $item['type'],
                'type_id' => $item['type_id'],
                'amounts' => $item['amounts'],
                'poundage' => $item['poundage'],
                'poundage_rate' => $item['poundage_rate'],
                'pay_way' => $withdrawTotal['pay_way'],
                'status' => 0,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        return IncomeWithdraw::insert($data);
    }

}