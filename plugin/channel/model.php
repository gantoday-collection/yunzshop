<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}
/**
* channel插件方法类
*
* 
* @package   渠道商插件公共方法
* @author    Yangyang<yangyang@yunzshop.com>
* @version   v1.0
*/
if (!class_exists('ChannelModel')) {
	class ChannelModel extends PluginModel
	{
		/**
		  * 获取渠道商基础设置
		  *
      	  * @return array $set
		  */
		public function getSet()
		{
			$set = parent::getSet();
			return $set;
		}
		/**
		  * 获取我的指定商品库存 
		  *
		  * @param string $openid, int $goodsid 商品id int $optionid 规格id
		  * @return int $stock
		  */
		public function getMyOptionStock($openid, $goodsid, $optionid)
		{
			global $_W;
			$stock = pdo_fetchcolumn("SELECT stock_total FROM " . tablename('sz_yi_channel_stock') . " WHERE uniacid={$_W['uniacid']} AND openid='{$openid}' AND goodsid={$goodsid} AND optionid={$optionid}");
			return $stock;
		}
		/**
		  * 获取上级渠道商库存最多的 
		  *
		  * @param string $openid, int $goodsid 商品id int $optionid 规格id
		  * @return array $my_superior
		  */
		public function getSuperiorStock($openid, $goodsid, $optionid)
		{
			global $_W;
			$my_agent_openids = $this->getAgentOpenids($openid);
			if (!empty($my_agent_openids)) {
				$stock = pdo_fetch("SELECT * FROM " . tablename('sz_yi_channel_stock') . " WHERE uniacid={$_W['uniacid']} AND openid in ({$my_agent_openids}) AND goodsid={$goodsid} AND optionid={$optionid}  ORDER BY stock_total DESC");
				return $stock;
			}
		}
		/**
		  * 获取所有上级的openid 
		  *
		  * @param string $openid
		  * @return array $my_agent_openids
		  */
		public function getAgentOpenids($openid)
		{
			global $_W;
			$my_agent_openids = array();
			$member = m('member')->getInfo($openid);
			if (empty($member['agentid'])) {
				return $my_agent_openids;
			}
			$agent = m('member')->getMember($member['agentid']);
			$my_agent_openids[] = "'".$agent['openid']."'";
			if (empty($agent['agentid'])) {
				$my_agent_openids = implode(',', $my_agent_openids);
				return $my_agent_openids;
			} else {
				$this->getAgentOpenids($agent['openid']);
			}
		}
		/**
		  * 获取自己和上级的渠道商详细信息 
		  *
		  * @param string $openid, int $goodsid='', int $optionid='', int $total=''
		  * @return array $channel_info
		  */
		public function getInfo($openid, $goodsid='', $optionid='', $total='')
		{
			global $_W;
			$channel_info = array();
			$member = m('member')->getInfo($openid);
			if (empty($member)) {
				return;
			}
			$ischannelmerchant = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_channel_merchant') . " WHERE uniacid={$_W['uniacid']} AND openid='{$openid}'");
			$set = $this->getSet();
			if (!empty($ischannelmerchant)) {
				$lower_openids = array();
				foreach ($ischannelmerchant as $value) {
					$lower_openids[] = "'".$value['lower_openid']."'";
				}
				$lower_openids = implode(',', $lower_openids);
				$lower_order_money = pdo_fetchcolumn("SELECT sum(og.price) FROM " . tablename('sz_yi_order') . " o LEFT JOIN " . tablename('sz_yi_order_goods') . " og on og.orderid=o.id WHERE o.uniacid={$_W['uniacid']} AND o.status>=3 AND o.iscmas=0 AND og.ischannelpay=1 AND o.openid in ({$lower_openids})");
				$lower_order_money = number_format($lower_order_money*$set['setprofitproportion']/100,2);
			} else {
				$lower_openids = 0;
				$lower_order_money = 0;
			}
			$channel_info['channel']['lower_openids'] = $lower_openids;
			$channel_info['channel']['lower_order_money'] = $lower_order_money;

			$channel_info['channel']['dispatchprice'] = pdo_fetchcolumn("SELECT ifnull(sum(dispatchprice),0) FROM " . tablename('sz_yi_order') . " WHERE uniacid={$_W['uniacid']} AND status>=3 AND iscmas=0 AND ischannelself=1 AND openid='{$openid}'");

            $channel_info['channel']['ordercount'] = pdo_fetchcolumn("SELECT count(o.id) FROM " . tablename('sz_yi_order_goods') . " og left join " .tablename('sz_yi_order') . " o on (o.id=og.orderid) WHERE og.channel_id={$member['id']} AND o.userdeleted=0 AND o.deleted=0 AND o.uniacid={$_W['uniacid']} ");

            $channel_info['channel']['commission_total'] = number_format(pdo_fetchcolumn("SELECT sum(apply_money) FROM " . tablename('sz_yi_channel_apply') . " WHERE uniacid={$_W['uniacid']} AND openid='{$openid}'"), 2);
            $channel_info['channel']['commission_pay_total'] = number_format(pdo_fetchcolumn("SELECT sum(apply_money) FROM " . tablename('sz_yi_channel_apply') . " WHERE uniacid={$_W['uniacid']} AND openid='{$openid}' AND status = 3"), 2);

            $channel_info['channel']['commission_ok'] = pdo_fetchcolumn("SELECT ifnull(sum(og.price),0) FROM " . tablename('sz_yi_order_goods') . " og left join " .tablename('sz_yi_order') . " o on (o.id=og.orderid) WHERE o.uniacid={$_W['uniacid']} AND og.channel_id={$member['id']} AND o.status=3 AND og.channel_apply_status=0 ");

            $channel_info['channel']['mychannels'] = pdo_fetchall("SELECT * FROM " .tablename('sz_yi_member') . " WHERE uniacid={$_W['uniacid']} AND channel_level<>0 AND agentid={$member['id']}");

            $level = pdo_fetch("SELECT * FROM " . tablename('sz_yi_channel_level') . " WHERE uniacid={$_W['uniacid']} AND id={$member['channel_level']}");
            if (!empty($level)) {
            	if (!empty($goodsid)) {
            		$up_level = $this->getUpChannel($openid, $goodsid, $optionid='', $total);
            		if (!empty($optionid)) {
            			$up_level = $this->getUpChannel($openid, $goodsid, $optionid, $total);
            		}
            	} else {
            		$up_level = $this->getUpChannel($openid);
            	}
            	$channel_info['my_level'] = $level;
            	$channel_info['up_level'] = $up_level;
            	return $channel_info;
            } else {
            	$up_level = $this->getUpChannel($openid);
            	$channel_info['up_level'] = $up_level;
            	return $channel_info;
            }
		}
		/**
		  * 获取渠道商等级权重与库存条件满足的上级openid
		  *
		  * @param string $openid, int $goodsid='', int $optionid='', int $total=''
		  * @return array $up_level
		  */
		public function getUpChannel($openid, $goodsid='', $optionid='', $total='')
		{
			global $_W;
			$member = m('member')->getInfo($openid);
			$member['level_num'] = pdo_fetchcolumn("SELECT level_num FROM " . tablename('sz_yi_channel_level') . " WHERE uniacid={$_W['uniacid']} AND id={$member['channel_level']}");
			if (empty($member['level_num'])) {
				$member['level_num'] = -1;
			}
			if (empty($member['agentid'])) {
				return;
			}
			$up_channel = pdo_fetch("SELECT * FROM " . tablename('sz_yi_member') . " WHERE uniacid={$_W['uniacid']} AND id={$member['agentid']}");
			if (!empty($up_channel['channel_level'])) {
				$up_level = pdo_fetch("SELECT * FROM " . tablename('sz_yi_channel_level') . " WHERE uniacid={$_W['uniacid']} AND id={$up_channel['channel_level']}");
				if (!empty($goodsid)) {
					$condtion = " AND goodsid={$goodsid}";
					if (!empty($optionid)) {
						$condtion = " AND goodsid={$goodsid} AND optionid={$optionid} AND stock_total>={$total}";
					}
					$up_stock = pdo_fetch("SELECT * FROM " . tablename('sz_yi_channel_stock') . " WHERE uniacid={$_W['uniacid']} AND openid='{$up_channel['openid']}' AND stock_total>0 {$condtion} AND stock_total>={$total}");
				} else {
					$up_stock = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_channel_stock') . " WHERE uniacid={$_W['uniacid']} AND openid='{$up_channel['openid']}' AND stock_total>0");
				}
				if ($up_level['level_num'] > $member['level_num'] && !empty($up_stock)) {
					$up_level['openid'] = $up_channel['openid'];
					$up_level['stock']	= $up_stock;
					return $up_level;
				} else {
					$this->getUpChannel($up_channel['openid']);
				}
			}
		}
		/**
		  * 获取等级权重小于等于自己的上级渠道商,如果开启推荐员,把两个人的openid与推荐员利润比例存入sz_yi_channel_merchant
		  *
		  * @param string $openid 用户openid
		  */
		public function getChannelNum($openid)
		{
			global $_W;
			$set = $this->getSet();
			//不为空为关闭
			if (!empty($set['closerecommenderchannel'])) {
				return;
			}
			$member = m('member')->getInfo($openid);
			$my_channel_level = $this->getLevel($openid);
			if (empty($member['agentid'])) {
				return;
			}
			$up_channel = pdo_fetch("SELECT * FROM " . tablename('sz_yi_member') . " WHERE uniacid={$_W['uniacid']} AND id={$member['agentid']}");
			if (empty($up_channel['channel_level'])) {
				return;
			}
			$up_channel_level = $this->getLevel($up_channel['openid']);
			if ($my_channel_level['level_num'] >= $up_channel_level['level_num']) {
				pdo_insert('sz_yi_channel_merchant', array(
					'uniacid'		=> $_W['uniacid'],
					'openid'		=> $up_channel['openid'],
					'lower_openid'	=> $openid,
					'commission'	=> $set['setprofitproportion']
					));
			} else {
				$this->getChannelNum($up_channel['openid']);
			}
		}
		/**
		  * 获取用户的渠道商等级详情
		  *
		  * @param string $openid 用户openid
		  * @return array $level
		  */
		public function getLevel($openid)
		{
			global $_W;
			if (empty($openid)) {
				return false;
			}
			$member = m('member')->getMember($openid);
			if (empty($member['channel_level'])) {
				return false;
			}
			$level = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_channel_level') . ' WHERE uniacid=:uniacid AND id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $member['channel_level']));
			return $level;
		}
		/**
		  * 渠道商根据直属下线升级
		  *
		  * @param string $openid 用户openid
		  */
		public function upgradeLevelByAgent($openid)
		{
			global $_W;
			if (empty($openid)) {
				return false;
			}
			$set = $this->getSet();
			$member = m('member')->getMember($openid);
			if (empty($member)) {
				return;
			}
			$my_agents = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('sz_yi_member') . " WHERE uniacid={$_W['uniacid']} AND agentid={$member['id']} AND ischannel=1 AND channel_level>0");
			if ($set['become'] == 1) {
				$my_level = $this->getLevel($openid);
				$up_level_num = $my_level['level_num'] + 1;
				$channel_level = pdo_fetch("SELECT * FROM " . tablename('sz_yi_channel_level') . " WHERE uniacid={$_W['uniacid']} AND level_num={$up_level_num}");
				if ($my_agents >= $channel_level['team_count']) {
					pdo_update('sz_yi_member', array('channel_level' => $channel_level['id']), array('uniacid' => $_W['uniacid'], 'id' => $member['id']));
					$this->getChannelNum($member['openid']);
					//通知
				}
			}
		}
		/**
		  * 根据进货金额或进货次数升级
		  *
		  * @param int $orderid 订单的id
		  */
		public function checkOrderFinishOrPay($orderid = '')
		{
			global $_W, $_GPC;
			if (empty($orderid)) {
				return;
			}
			$set = $this->getSet();
			if(empty($set['become'])){
				return;
			}
			$order = pdo_fetch('SELECT id,openid,ordersn,goodsprice,agentid,paytime,finishtime FROM ' . tablename('sz_yi_order') . ' WHERE id=:id AND status>=1 AND uniacid=:uniacid limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid']));
			if (empty($order)) {
				return;
			}
			$openid = $order['openid'];
			$member = m('member')->getMember($openid);
			if (empty($member)) {
				return;
			}
			if ($set['become'] == 2 || $set['become'] == 3) {
				$level = $this->getLevel($openid);
				$orderinfo = pdo_fetch('SELECT sum(og.realprice) AS ordermoney,count(distinct og.orderid) AS ordercount FROM ' . tablename('sz_yi_order') . ' o ' . ' LEFT JOIN  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' WHERE o.openid=:openid AND o.status>=3 AND o.uniacid=:uniacid AND og.ischannelpay=1 limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
				$ordermoney = $orderinfo['ordermoney'];
				$ordercount = $orderinfo['ordercount'];
				$up_level_num = $level['level_num'] + 1;
				$up_level = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_channel_level') . " WHERE uniacid=:uniacid  AND level_num=:level_num", array(':uniacid' => $_W['uniacid'],':level_num' => $up_level_num));
				if (empty($up_level)) {
					return;
				}
				if (!empty($level['id'])) {
					if ($level['id'] == $up_level['id']) {
						return;
					}
				}
				if ($set['become'] == 2) {
					if ($up_level['team_count'] > $ordermoney) {
						return;
					}
				} else if ($set['become'] == 3) {
					if ($up_level['team_count'] > $ordercount) {
						return;
					}
				}
				pdo_update('sz_yi_member', array('channel_level' => $up_level['id']), array('id' => $member['id']));
				$this->getChannelNum($member['openid']);
				//$this->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'oldlevel' => $level, 'newlevel' => $up_level,), TM_CHANNEL_UPGRADE);
			}
		}
		//后台通知模板未做
		/*function sendMessage($openid = '', $data = array(), $message_type = '')
		{
			global $_W, $_GPC;
			
			$set = $this->getSet();
		}*/
	}
}