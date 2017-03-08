<?php
/**
 * 商品权限关联表数据操作
 * Created by PhpStorm.
 * User: luckystar_D
 * Date: 2017/2/28
 * Time: 上午11:01
 */

namespace app\backend\modules\goods\models;


class Privilege extends \app\common\models\goods\Privilege
{
    public $timestamps = false;

    /**
     * 获取商品权限数据
     * @param int $goodsId
     * @return array
     */
    public static function getInfo($goodsId)
    {
        return self::getGoodsPrivilegeInfo($goodsId);
    }

    public static function relationSave($goodsId, $data, $operate)
    {
        if(!$goodsId){
            return false;
        }
        $privilegeModel = self::getModel($goodsId, $operate);
        //判断deleted
        if ($operate == 'deleted') {
            return $privilegeModel->delete();
        }
        $data['goods_id'] = $goodsId;
        $privilegeModel->setRawAttributes($data);
        $privilegeModel->show_levels = !empty($data['show_levels']) ? implode(',', $data['show_levels']) : '';
        $privilegeModel->buy_levels = !empty($data['buy_levels']) ? implode(',', $data['buy_levels']) : '';
        $privilegeModel->show_groups = !empty($data['show_groups']) ? implode(',', $data['show_groups']) : '';
        $privilegeModel->buy_groups = !empty($data['buy_groups']) ? implode(',', $data['buy_groups']) : '';
        $privilegeModel->once_buy_limit = !empty($data['once_buy_limit']) ? $data['once_buy_limit'] : '0';
        $privilegeModel->total_buy_limit = !empty($data['total_buy_limit']) ? $data['total_buy_limit']: '0';
        $privilegeModel->time_begin_limit = !empty($data['time_begin_limit']) ? strtotime($data['time_begin_limit']) : '0';
        $privilegeModel->time_end_limit = !empty($data['time_end_limit']) ? strtotime($data['time_end_limit']): '0';git
        return $privilegeModel->save();
    }

    public static function getModel($goodsId,$operate)
    {
        $model = false;
        if($operate != 'created') {
            $model = static::where(['goods_id' => $goodsId])->first();
        }
        !$model && $model =  new static;

        return $model;
    }

    /**
     * 商品分享权限数据添加
     * @param array $privilegeInfo
     * @return bool
     */
    public static function createdPrivilege($privilegeInfo)
    {
        return self::insert($privilegeInfo);
    }

    /**
     * 商品分享权限数据更新
     * @param array $privilegeInfo
     * @return mixed
     */
    public static function updatedPrivilege($goodsId, $privilegeInfo)
    {
        return self::where('goods_id', $goodsId)->update($privilegeInfo);
    }

    /**
     * 商品分享权限数据删除
     * @param int $goodsId
     * @return mixed
     */
    public static function deletedPrivilege($goodsId)
    {
        return self::where('goods_id', $goodsId)->delete();
    }
}