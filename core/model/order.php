<?php
/*=============================================================================
#     FileName: order.php
#         Desc: 订单类
#       Author: RainYang - https://github.com/rainyang
#        Email: rainyang2012@qq.com
#     HomePage: http://rainyang.github.io
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:34:01
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Sz_DYi_Order
{
    function getDispatchPrice($dephp_0, $dephp_1, $dephp_2 = -1){
        if (empty($dephp_1)){
            return 0;
        }
        $dephp_3 = 0;
        if ($dephp_2 == -1){
            $dephp_2 = $dephp_1['calculatetype'];
        }
        if ($dephp_2 == 1){
            if ($dephp_0 <= $dephp_1['firstnum']){
                $dephp_3 = floatval($dephp_1['firstnumprice']);
            }else{
                $dephp_3 = floatval($dephp_1['firstnumprice']);
                $dephp_4 = $dephp_0 - floatval($dephp_1['firstnum']);
                $dephp_5 = floatval($dephp_1['secondnum']) <= 0 ? 1 : floatval($dephp_1['secondnum']);
                $dephp_6 = 0;
                if ($dephp_4 % $dephp_5 == 0){
                    $dephp_6 = ($dephp_4 / $dephp_5) * floatval($dephp_1['secondnumprice']);
                }else{
                    $dephp_6 = ((int) ($dephp_4 / $dephp_5) + 1) * floatval($dephp_1['secondnumprice']);
                }
                $dephp_3 += $dephp_6;
            }
        }else{
            if ($dephp_0 <= $dephp_1['firstweight']){
                $dephp_3 = floatval($dephp_1['firstprice']);
            }else{
                $dephp_3 = floatval($dephp_1['firstprice']);
                $dephp_4 = $dephp_0 - floatval($dephp_1['firstweight']);
                $dephp_5 = floatval($dephp_1['secondweight']) <= 0 ? 1 : floatval($dephp_1['secondweight']);
                $dephp_6 = 0;
                if ($dephp_4 % $dephp_5 == 0){
                    $dephp_6 = ($dephp_4 / $dephp_5) * floatval($dephp_1['secondprice']);
                }else{
                    $dephp_6 = ((int) ($dephp_4 / $dephp_5) + 1) * floatval($dephp_1['secondprice']);
                }
                $dephp_3 += $dephp_6;
            }
        }
        return $dephp_3;
    }

    /*
    function getDispatchPrice($weight, $d)
    {
        if (empty($d)) {
            return 0;
        }
        $price = 0;
        if ($weight <= $d['firstweight']) {
            $price = floatval($d['firstprice']);
        } else {
            $price         = floatval($d['firstprice']);
            $secondweight  = $weight - floatval($d['firstweight']);
            $dsecondweight = floatval($d['secondweight']) <= 0 ? 1 : floatval($d['secondweight']);
            $secondprice   = 0;
            if ($secondweight % $dsecondweight == 0) {
                $secondprice = ($secondweight / $dsecondweight) * floatval($d['secondprice']);
            } else {
                $secondprice = ((int) ($secondweight / $dsecondweight) + 1) * floatval($d['secondprice']);
            }
            $price += $secondprice;
        }
        return $price;
    }
     */

    /*
    function getCityDispatchPrice($_var_6, $_var_7, $weight, $d)
    {
        if (is_array($_var_6) && count($_var_6) > 0) {
            foreach ($_var_6 as $_var_8) {
                $_var_9 = explode(';', $_var_8['citys']);
                if (in_array($_var_7, $_var_9) && !empty($_var_9)) {
                    return $this->getDispatchPrice($weight, $_var_8);
                }
            }
        }
        return $this->getDispatchPrice($weight, $d);
    }
     */

    function getCityDispatchPrice($dephp_7, $dephp_8, $dephp_0, $dephp_1){
        if (is_array($dephp_7) && count($dephp_7) > 0){
            foreach ($dephp_7 as $dephp_9){
                $dephp_10 = explode(';', $dephp_9['citys']);
                if (in_array($dephp_8, $dephp_10) && !empty($dephp_10)){
                    return $this -> getDispatchPrice($dephp_0, $dephp_9, $dephp_1['calculatetype']);
                }
            }
        }
        return $this -> getDispatchPrice($dephp_0, $dephp_1);
    }

    /**
     * 支付完成回调方法
     * @param params array
     * @return array()
     * modify RainYang 2016.4.7
     */
    public function payResult($params)
    {
        global $_W;
        $fee     = $params['fee'];
        $uniacid = $_W['uniacid'];
        $data    = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        $ordersn = $params['tid'];
        $orderall = pdo_fetchall("select * from " . tablename('sz_yi_order') . ' where ordersn_general=:ordersn_general and uniacid=:uniacid', array(
            ':ordersn_general' => $ordersn,
            ':uniacid' => $uniacid
        ));
        if(count($orderall) > 1){
            $order = array();
            $order['ordersn'] = $ordersn;
            $orderid = array();
            foreach ($orderall as $key => $val) {
                $order['price']           += $val['price'];
                $order['deductcredit2']   += $val['deductcredit2'];
                $order['ordersn2']        += $val['ordersn2'];
                $orderid[]                 = $val['id'];
            }
            $order['dispatchtype'] = $val['dispatchtype'];
            $order['addressid'] = $val['addressid'];
            $order['isvirtual'] = $val['isvirtual'];
            $order['carrier'] = $val['carrier'];
            $order['status'] = $val['status'];
            $order['virtual'] = $val['virtual'];
            $order['couponid'] = $val['couponid'];
        }else{
            $order   = $orderall[0];
            $orderid = $order['id'];
        }
        //验证paylog里金额是否与订单金额一致
        $log = pdo_fetch('select * from ' . tablename('core_paylog') . ' where `uniacid`=:uniacid and fee=:fee and `module`=:module and `tid`=:tid limit 1',
            array(
            ':uniacid' => $_W['uniacid'],
            ':module' => 'sz_yi',
            ':fee' => $fee,
            ':tid' => $ordersn
        ));
        if (empty($log)) {
            show_json(-1, '订单金额错误, 请重试!');
            exit;
        }

        //$orderid = $order['id'];
        if ($params['from'] == 'return') {
            $address = false;
            if (empty($order['dispatchtype'])) {
                $address = pdo_fetch('select realname,mobile,address from ' . tablename('sz_yi_member_address') . ' where id=:id limit 1', array(
                    ':id' => $order['addressid']
                ));
            }
            $carrier = false;
            if ($order['dispatchtype'] == 1 || $order['isvirtual'] == 1) {
                $carrier = unserialize($order['carrier']);
            }
            if ($params['type'] == 'cash') {
                return array(
                    'result' => 'success',
                    'order' => $order,
                    'address' => $address,
                    'carrier' => $carrier
                );
            } else {
                //多供应商支付成功条件
                if(is_array($orderid)){
                    $orderids     = implode(',', $orderid);
                    $order_update = "id in ({$orderids})";
                    $orderdetail_where = "o.id in ({$orderids})";
                    $goods_where = "og.orderid in ({$orderids})";
                }else{
                    $order_update = "id = ".$orderid;
                    $orderdetail_where = "o.id = {$orderid}";
                    $goods_where = "og.orderid = {$orderid}";
                }
                if ($order['status'] == 0) {
                    $pv = p('virtual');
                    if (!empty($order['virtual']) && $pv) {
                        $pv->pay($order);
                    } else {
                        if (p('channel')) {
                            if ($params['ischannelpay'] == 1) {
                                pdo_query('update ' . tablename('sz_yi_order') . " set status=3, paytime=".time().", finishtime=".time().", ordersn_general=ordersn where {$order_update} and uniacid='{$uniacid}' ");
                            } else {
                                pdo_query('update ' . tablename('sz_yi_order') . " set status=1, paytime=".time().", ordersn_general=ordersn where {$order_update} and uniacid='{$uniacid}' ");
                            }
                        } else {
                            pdo_query('update ' . tablename('sz_yi_order') . " set status=1, paytime=".time().", ordersn_general=ordersn where {$order_update} and uniacid='{$uniacid}' ");
                        }
                        if ($order['deductcredit2'] > 0) {
                            $shopset = m('common')->getSysset('shop');
                            m('member')->setCredit($order['openid'], 'credit2', -$order['deductcredit2'], array(
                                0,
                                $shopset['name'] . "余额抵扣: {$order['deductcredit2']} 订单号: " . $order['ordersn']
                            ));
                        }
                        if(is_array($orderid)){
                            foreach ($orderall as $k => $v) {
                                $this->setStocksAndCredits($v['id'], 1);
                                if (p('coupon') && !empty($v['couponid'])) {
                                    p('coupon')->backConsumeCoupon($v['id']);
                                }
                                m('notice')->sendOrderMessage($v['id']);
                                if (p('commission')) {
                                    p('commission')->checkOrderPay($v['id']);
                                }
                            }
                        }else{
                            $this->setStocksAndCredits($orderid, 1);
                            if (p('coupon') && !empty($order['couponid'])) {
                                p('coupon')->backConsumeCoupon($orderid);
                            }
                            m('notice')->sendOrderMessage($orderid);
                            if (p('commission')) {
                                p('commission')->checkOrderPay($orderid);
                            }
                        }   
                    }
                }
                
                
                $orderdetail=pdo_fetch("select o.dispatchprice,o.ordersn,o.price,og.optionname as optiontitle,og.optionid,og.total from " .tablename('sz_yi_order'). " o left join " .tablename('sz_yi_order_goods').  "og on og.orderid = o.id where {$orderdetail_where} and o.uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
                $sql = 'SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid FROM ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where ' . $goods_where . ' order by og.id asc';
                $orderdetail['goods1'] = set_medias(pdo_fetchall($sql), 'thumb');
                $pluginlove = p('love');
                if($pluginlove){
                   $pluginlove->checkOrder($goods_where, $order['openid'], 0);
                }
                $orderdetail['goodscount'] = count($orderdetail['goods1']);
                return array(
                    'result' => 'success',
                    'order' => $order,
                    'address' => $address,
                    'carrier' => $carrier,
                    'virtual' => $order['virtual'],
                    'goods'=>$orderdetail

                );
            }
        }
    }
    
    function setStocksAndCredits($orderid = '', $type = 0)
    {
        global $_W;
        $order   = pdo_fetch('select id,ordersn,price,openid,dispatchtype,addressid,carrier,status from ' . tablename('sz_yi_order') . ' where id=:id limit 1', array(
            ':id' => $orderid
        ));
        $cond = "";
        if (p('channel')) {
            $cond    = ',og.channel_id,og.ischannelpay';
        }
        $goods   = pdo_fetchall("select og.id,og.goodsid" . $cond . ",og.total,g.totalcnf,og.realprice, g.credit,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal from " . tablename('sz_yi_order_goods') . " og " . " left join " . tablename('sz_yi_goods') . " g on g.id=og.goodsid " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array(
            ':uniacid' => $_W['uniacid'],
            ':orderid' => $orderid
        ));
        $credits = 0;
        foreach ($goods as $g) {
            $stocktype = 0;
            if ($type == 0) {
                if ($g['totalcnf'] == 0) {
                    $stocktype = -1;
                }
            } else if ($type == 1) {
                if ($g['totalcnf'] == 1) {
                    $stocktype = -1;
                }
            } else if ($type == 2) {
                if ($order['status'] >= 1) {
                    if ($g['totalcnf'] == 1) {
                        $stocktype = 1;
                    }
                } else {
                    if ($g['totalcnf'] == 0) {
                        $stocktype = 1;
                    }
                }
            }
            if (!empty($stocktype)) {
                if (!empty($g['optionid'])) {
                    if (p('channel')) {
                        if (!empty($g['channel_id'])) {
                            $my_info = p('channel')->getInfo($order['openid'],$g['goodsid'],$g['optionid'],$g['total']);
                            if (!empty($my_info['up_level']['stock'])) {
                                $stock = -1;
                                if ($stocktype == 1) {
                                    $stock = $my_info['up_level']['stock']['stock_total'] + $g['total'];
                                } else if ($stocktype == -1) {
                                    $stock = $my_info['up_level']['stock']['stock_total'] - $g['total'];
                                }
                                if ($stock != -1) {
                                    pdo_update('sz_yi_channel_stock', array(
                                        'stock_total' => $stock
                                    ), array(
                                        'uniacid'   => $_W['uniacid'],
                                        'goodsid'   => $g['goodsid'],
                                        'openid'    => $my_info['up_level']['openid'],
                                        'optionid'  => $g['optionid']
                                    ));
                                    $channel = true;
                                }
                                $goods_price = pdo_fetchcolumn("SELECT marketprice FROM " . tablename('sz_yi_goods') . " WHERE uniacid={$_W['uniacid']} AND id={$g['goodsid']}");
                                $log_data = array(
                                    'goodsid'       => $g['goodsid'],
                                    'optionid'      => $g['optionid'],
                                    'order_goodsid' => $g['id'],
                                    'uniacid'       => $_W['uniacid'],
                                    'every_turn'    => $g['total'],
                                    'goods_price'   => $goods_price,
                                    'surplus_stock' => $stock
                                    );
                                if (!empty($g['ischannelpay'])) {
                                    $log_data['every_turn_price'] = $goods_price*$my_info['my_level']['purchase_discount']/100;
                                    $log_data['every_turn_discount'] = $my_info['my_level']['purchase_discount'];
                                    $log_data['type'] = 2;
                                    pdo_insert('sz_yi_channel_stock_log', $log_data);
                                } else {
                                    $log_data['every_turn_price'] = $goods_price;
                                    $log_data['every_turn_discount'] = 0;
                                    $log_data['type'] = 3;
                                    pdo_insert('sz_yi_channel_stock_log', $log_data);
                                }
                            }
                        }
                    }
                    if (empty($channel)) {
                        $option = m('goods')->getOption($g['goodsid'], $g['optionid']);
                        if (!empty($option) && $option['stock'] != -1) {
                            $stock = -1;
                            if ($stocktype == 1) {
                                $stock = $option['stock'] + $g['total'];
                            } else if ($stocktype == -1) {
                                $stock = $option['stock'] - $g['total'];
                                $stock <= 0 && $stock = 0;
                            }
                            if ($stock != -1) {
                                pdo_update('sz_yi_goods_option', array(
                                    'stock' => $stock
                                ), array(
                                    'uniacid' => $_W['uniacid'],
                                    'goodsid' => $g['goodsid'],
                                    'id' => $g['optionid']
                                ));
                            }
                        }
                    }
                }
                if (p('channel')) {
                    if (empty($channel)) {
                        if (!empty($g['channel_id'])) {
                            $my_info = p('channel')->getInfo($order['openid'],$g['goodsid'],0,$g['total']);
                            if (!empty($my_info['up_level']['stock'])) {
                                $totalstock = -1;
                                if ($stocktype == 1) {
                                    $totalstock = $my_info['up_level']['stock']['stock_total'] + $g['total'];
                                } else if ($stocktype == -1) {
                                    $totalstock = $my_info['up_level']['stock']['stock_total'] - $g['total'];
                                }
                                if ($totalstock != -1) {
                                    pdo_update('sz_yi_channel_stock', array(
                                        'stock_total' => $totalstock
                                    ), array(
                                        'uniacid' => $_W['uniacid'],
                                        'goodsid' => $g['goodsid'],
                                        'openid'  => $my_info['up_level']['openid']
                                    ));
                                    $channels = true;
                                }
                                $goods_price = pdo_fetchcolumn("SELECT marketprice FROM " . tablename('sz_yi_goods') . " WHERE uniacid={$_W['uniacid']} AND id={$g['goodsid']}");
                                $log_data = array(
                                    'goodsid'       => $g['goodsid'],
                                    'order_goodsid' => $g['id'],
                                    'uniacid'       => $_W['uniacid'],
                                    'every_turn'    => $g['total'],
                                    'goods_price'   => $goods_price,
                                    'surplus_stock' => $totalstock
                                    );
                                if (!empty($g['ischannelpay'])) {
                                    $log_data['every_turn_price'] = $goods_price*$my_info['my_level']['purchase_discount']/100;
                                    $log_data['every_turn_discount'] = $my_info['my_level']['purchase_discount'];
                                    $log_data['type'] = 2;
                                    pdo_insert('sz_yi_channel_stock_log', $log_data);
                                } else {
                                    $log_data['every_turn_price'] = $goods_price;
                                    $log_data['every_turn_discount'] = 0;
                                    $log_data['type'] = 3;
                                    pdo_insert('sz_yi_channel_stock_log', $log_data);
                                }
                            }
                        }
                    }
                }
                if (empty($channels)) {
                    if (!empty($g['goodstotal']) && $g['goodstotal'] != -1) {
                        $totalstock = -1;
                        if ($stocktype == 1) {
                            $totalstock = $g['goodstotal'] + $g['total'];
                        } else if ($stocktype == -1) {
                            $totalstock = $g['goodstotal'] - $g['total'];
                            $totalstock <= 0 && $totalstock = 0;
                        }
                        if ($totalstock != -1) {
                            pdo_update('sz_yi_goods', array(
                                'total' => $totalstock
                            ), array(
                                'uniacid' => $_W['uniacid'],
                                'id' => $g['goodsid']
                            ));
                        }
                    }
                }
            }
            $gcredit = trim($g['credit']);
            if (!empty($gcredit)) {
                if (strexists($gcredit, '%')) {
                    $credits += intval(floatval(str_replace('%', '', $gcredit)) / 100 * $g['realprice']);
                } else {
                    $credits += intval($g['credit']) * $g['total'];
                }
            }
            if ($type == 0) {
                pdo_update('sz_yi_goods', array(
                    'sales' => $g['sales'] + $g['total']
                ), array(
                    'uniacid' => $_W['uniacid'],
                    'id' => $g['goodsid']
                ));
            } elseif ($type == 1) {
                if ($order['status'] >= 1) {
                    $salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(
                        ':goodsid' => $g['goodsid'],
                        ':uniacid' => $_W['uniacid']
                    ));
                    pdo_update('sz_yi_goods', array(
                        'salesreal' => $salesreal
                    ), array(
                        'id' => $g['goodsid']
                    ));
                }
            }
        }
        if ($credits > 0) {
            $shopset = m('common')->getSysset('shop');
            if ($type == 1) {
                m('member')->setCredit($order['openid'], 'credit1', $credits, array(
                    0,
                    $shopset['name'] . '购物积分 订单号: ' . $order['ordersn']
                ));
            } elseif ($type == 2) {
                if ($order['status'] >= 1) {
                    m('member')->setCredit($order['openid'], 'credit1', -$credits, array(
                        0,
                        $shopset['name'] . '购物取消订单扣除积分 订单号: ' . $order['ordersn']
                    ));
                }
            }
        }
    }
    function getDefaultDispatch($supplier_uid = 0){
        global $_W;
        $sql = 'select * from ' . tablename('sz_yi_dispatch') . ' where isdefault=1 and uniacid=:uniacid and enabled=1 and supplier_uid=:supplier_uid Limit 1';
        $prem = array(':supplier_uid' => $supplier_uid, ':uniacid' => $_W['uniacid']);
        $DefaultDispatch = pdo_fetch($sql, $prem);
        return $DefaultDispatch;
    }
    function getNewDispatch($supplier_uid = 0){
        global $_W;
        $sql = 'select * from ' . tablename('sz_yi_dispatch') . ' where uniacid=:uniacid and enabled=1 and supplier_uid=:supplier_uid order by id desc Limit 1';
        $prem = array(':supplier_uid' => $supplier_uid,':uniacid' => $_W['uniacid']);
        $NewDispatch = pdo_fetch($sql, $prem);
        return $NewDispatch;
    }
    function getOneDispatch($dispatch_id, $supplier_uid = 0){
        global $_W;
        $sql = 'select * from ' . tablename('sz_yi_dispatch') . ' where id=:id and uniacid=:uniacid and enabled=1 and supplier_uid=:supplier_uid Limit 1';
        $prem = array(':supplier_uid' => $supplier_uid, ':id' => $dispatch_id, ':uniacid' => $_W['uniacid']);
        $OneDispatch = pdo_fetch($sql, $prem);
        return $OneDispatch;
    }
}
