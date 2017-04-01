<?php
/**
 * Created by PhpStorm.
 * User: shenyang
 * Date: 2017/2/23
 * Time: 上午11:11
 */

namespace app\frontend\modules\order\controllers;

use app\common\components\ApiController;
use app\common\components\BaseController;
use app\common\events\discount\OnDiscountInfoDisplayEvent;
use app\common\events\dispatch\OnDispatchTypeInfoDisplayEvent;
use app\common\exceptions\AppException;
use app\frontend\modules\goods\services\GoodsService;
use app\frontend\modules\member\models\MemberCart;
use app\frontend\modules\member\services\MemberService;
use app\frontend\modules\order\services\OrderService;
use app\frontend\modules\shop\services\ShopService;

class PreGeneratedController extends ApiController
{
    private $param;
    private $memberCarts;
    public function index()
    {

        $this->param['goods'][] = [
            'goods_id'=>\YunShop::request()->get('goods_id'),
            'total'=>\YunShop::request()->get('total'),
            'option_id'=>\YunShop::request()->get('option_id'),
        ];
        $this->memberCarts[] = (new MemberCart($this->param['goods'][0]));

        $this->run();
    }

    public function cart()
    {
        if(!isset($_GET['cart_ids'])){
            return $this->errorJson('请选择要结算的商品');
        }
        if(!is_array($_GET['cart_ids'])){
            $cartIds = explode(',',$_GET['cart_ids']);
        }
        if(!count($cartIds)){
            return $this->errorJson('参数格式有误');
        }
        $cart = MemberCart::getCartsByIds($cartIds);
        //dd($cart);exit;
        $this->memberCarts = $cart;
        $this->run();
    }

    private function run()
    {

        $member_model = MemberService::getCurrentMemberModel();
        //dd($member_model);exit;
        if(!isset($member_model)){
            throw new AppException('用户登录状态过期');
        }
        $shop_model = ShopService::getCurrentShopModel();
        $order_goods_models = OrderService::getOrderGoodsModels($this->memberCarts);
        if(!count($order_goods_models)){
            throw new AppException('未找到商品');
        }
        //dd($order_goods_models);exit;
        list($result, $message) = GoodsService::GoodsListAvailable($order_goods_models);
        if ($result === false) {
            throw new AppException('$message');
        }

        $order_model = OrderService::getPreGeneratedOrder($order_goods_models, $member_model, $shop_model);

        $order = $order_model->toArray();

        $data = [
            'order' => $order
        ];
        $data = array_merge($data, $this->getDiscountEventData($order_model), $this->getDispatchEventData($order_model));
        //var_dump($data);
        //dd($data);
        return $this->successJson('成功',$data);
    }

    private function getDiscountEventData($order_model)
    {
        $Event = new OnDiscountInfoDisplayEvent($order_model);
        event($Event);
        return $Event->getMap();
    }

    private function getDispatchEventData($order_model)
    {
        $Event = new OnDispatchTypeInfoDisplayEvent($order_model);
        event($Event);
        return ['dispatch' => $Event->getMap()];
    }
}