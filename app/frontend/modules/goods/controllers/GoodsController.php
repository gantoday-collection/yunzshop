<?php
namespace app\frontend\modules\goods\controllers;

use app\common\components\BaseController;
use app\common\models\Category;
use app\common\models\Goods;
use app\common\models\GoodsCategory;
use app\common\models\GoodsSpecItem;
use app\frontend\modules\goods\services\GoodsService;

/**
 * Created by PhpStorm.
 * User: Rui
 * Date: 2017/3/3
 * Time: 22:16
 */
class GoodsController extends BaseController
{
    public function getGoods()
    {
        $id = \YunShop::request()->id;
        //$goods = new Goods();
        $goodsModel = Goods::with('hasManyParams')->with('hasManySpecs')->find($id);
        if (!$goodsModel) {
            $this->errorJson('商品不存在.');
        }

        foreach ($goodsModel->hasManySpecs as &$spec) {
            $spec['specitem'] = GoodsSpecItem::where('specid', $spec['id'])->get();
        }

        //return $this->successJson($goodsModel);
        $this->successJson($goodsModel);
    }

    public function getGoodsList()
    {
        $category_array = \YunShop::request()->category_id ? ['id' => \YunShop::request()->category_id] : [];
        //dd($category_array);
        $goodsList = Category::uniacid()->where($category_array)->with(
            ['goodsCategories' => function ($query) {
                return $query->select(['goods_id', 'category_id'])->with(
                    [
                        'goods' => function ($query1) {
                            return $query1->select(['id', 'title', 'thumb', 'price'])->where('status', '1');
                        }
                    ]);
            }])->first();
        //dd($goodsList);
        // goodsList[0]中的0能否去掉?
        $goodsList = $goodsList->goodsCategories->filter(function($item){
            return $item->goods != null;
        })->all();
        //dd($goodsList);

        if (empty($goodsList)) {
            $this->errorJson('此分类下没有商品.');
        }
        $this->successJson($goodsList);
    }

    /**
     * @param $goods_id
     * @param null $option_id
     * @return bool|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function getGoodsCart()
    {


        $goodsService = new GoodsService();
        $goods = $goodsService->getGoodsByCart(\YunShop::request()->goods_id, \YunShop::request()->option_id);
        if (!$goods) {
            return false;
        }

        return $goods;
    }

}