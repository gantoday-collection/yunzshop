<?php
/*=============================================================================
#     FileName: 1.4.2.php
#         Desc:
#       Author: RainYang - https://github.com/rainyang
#        Email: rainyang2012@qq.com
#     HomePage: http://rainyang.github.io
#      Version: 0.0.1
#   LastChange: 2016-03-29 19:28:39
#      History:
=============================================================================*/

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('sz_yi_exhelper_express') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '单据分类 1为快递单 2为发货单',
  `expressname` varchar(255) DEFAULT '',
  `expresscom` varchar(255) NOT NULL DEFAULT '',
  `express` varchar(255) NOT NULL DEFAULT '',
  `width` decimal(10,2) DEFAULT '0.00',
  `datas` text,
  `height` decimal(10,2) DEFAULT '0.00',
  `bg` varchar(255) DEFAULT '',
  `isdefault` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_isdefault` (`isdefault`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('sz_yi_exhelper_senduser')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sendername` varchar(255) DEFAULT '' COMMENT '发件人',
  `sendertel` varchar(255) DEFAULT '' COMMENT '发件人联系电话',
  `sendersign` varchar(255) DEFAULT '' COMMENT '发件人签名',
  `sendercode` int(11) DEFAULT NULL COMMENT '发件地址邮编',
  `senderaddress` varchar(255) DEFAULT '' COMMENT '发件地址',
  `sendercity` varchar(255) DEFAULT NULL COMMENT '发件城市',
  `isdefault` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_isdefault` (`isdefault`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS ".tablename('sz_yi_exhelper_sys')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT 'localhost',
  `port` int(11) NOT NULL DEFAULT '8000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS  ".tablename('sz_yi_express')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('sz_yi_diyform_category'). " (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NULL DEFAULT 0 COMMENT '所属帐号' ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类名称' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS ".tablename('sz_yi_diyform_data'). " (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL DEFAULT 0 ,
`typeid`  int(11) NOT NULL DEFAULT 0 COMMENT '类型id' ,
`cid`  int(11) NULL DEFAULT 0 COMMENT '关联id' ,
`diyformfields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`fields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字符集' ,
`openid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '使用者openid' ,
`type`  tinyint(2) NULL DEFAULT 0 COMMENT '该数据所属模块' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_typeid` (`typeid`) USING BTREE ,
INDEX `idx_cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS ".tablename('sz_yi_diyform_temp'). " (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL DEFAULT 0 ,
`typeid`  int(11) NULL DEFAULT 0 ,
`cid`  int(11) NOT NULL DEFAULT 0 COMMENT '关联id' ,
`diyformfields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`fields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字符集' ,
`openid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '使用者openid' ,
`type`  tinyint(1) NULL DEFAULT 0 COMMENT '类型' ,
`diyformid`  int(11) NULL DEFAULT 0 ,
`diyformdata`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_cid` (`cid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS ".tablename('sz_yi_diyform_type'). " (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL DEFAULT 0 ,
`cate`  int(11) NULL DEFAULT 0 ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称' ,
`fields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字段集' ,
`usedata`  int(11) NOT NULL DEFAULT 0 COMMENT '已用数据' ,
`alldata`  int(11) NOT NULL DEFAULT 0 COMMENT '全部数据' ,
`status`  tinyint(1) NULL DEFAULT 1 COMMENT '状态' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_cate` (`cate`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;
";
pdo_query($sql);

pdo_query("ALTER TABLE  ".tablename('sz_yi_member')." CHANGE  `pwd`  `pwd` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");

if(!pdo_fieldexists('sz_yi_goods', 'cates')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD     `cates` text;");
}

if(!pdo_fieldexists('sz_yi_goods', 'diyformtype')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `diyformtype` tinyint(3) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_goods', 'manydeduct')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `manydeduct` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_goods', 'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `dispatchtype` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_goods', 'dispatchid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `dispatchid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_goods', 'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `dispatchprice`  decimal(10,2) DEFAULT '0.00';");
}



if(!pdo_fieldexists('sz_yi_goods', 'diyformid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `diyformid` int(11) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_goods', 'diymode')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD `diymode` tinyint(3) DEFAULT '0';");
}


pdo_query("UPDATE `ims_qrcode` SET `name` = 'SZ_YI_POSTER_QRCODE', `keyword`='SZ_YI_POSTER' WHERE `keyword` = 'EWEI_SHOP_POSTER'");

if(!pdo_fieldexists('sz_yi_member', 'regtype')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `regtype` tinyint(3) DEFAULT '1';");
}
if(!pdo_fieldexists('sz_yi_member', 'isbindmobile')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `isbindmobile` tinyint(3) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_member', 'isjumpbind')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `isjumpbind` tinyint(3) DEFAULT '0';");
}
//diy
if(!pdo_fieldexists('sz_yi_store', 'realname')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_store')." ADD `realname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('sz_yi_store', 'mobile')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_store')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('sz_yi_store', 'fetchtime')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_store')." ADD `fetchtime` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('sz_yi_store', 'type')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_store')." ADD `type` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_member', 'diymemberid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diymemberid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_member', 'isblack')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `isblack` tinyint(3) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_member', 'diymemberdataid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diymemberdataid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_member', 'diycommissionid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diycommissionid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_member', 'diycommissiondataid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diycommissiondataid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_member', 'diymemberfields')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diymemberfields` text NULL;");
}
if(!pdo_fieldexists('sz_yi_member', 'diymemberdata')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diymemberdata` text NULL;");
}
if(!pdo_fieldexists('sz_yi_member', 'diycommissionfields')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diycommissionfields` text NULL;");
}
if(!pdo_fieldexists('sz_yi_member', 'diycommissiondata')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member')." ADD    `diycommissiondata` text NULL;");
}
if(!pdo_fieldexists('sz_yi_member_cart', 'diyformdata')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member_cart')." ADD    `diyformdata` text NULL;");
}
if(!pdo_fieldexists('sz_yi_member_cart', 'diyformfields')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member_cart')." ADD    `diyformfields` text NULL;");
}
if(!pdo_fieldexists('sz_yi_member_cart', 'diyformdataid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member_cart')." ADD    `diyformdataid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_member_cart', 'diyformid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_member_cart')." ADD    `diyformid` int(11) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order', 'diyformid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD    `diyformid` int(11) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order_goods', 'openid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD    `openid` varchar(255) DEFAULT '';");
}

if(!pdo_fieldexists('sz_yi_order', 'storeid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD    `storeid` int(11) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order', 'diyformid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD    `diyformid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_order', 'diyformdata')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD    `diyformdata` text NULL;");
}
if(!pdo_fieldexists('sz_yi_order', 'diyformfields')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD    `diyformfields` text NULL;");
}

if(!pdo_fieldexists('sz_yi_order_goods', 'diyformdataid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD    `diyformdataid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_order_goods', 'diyformid')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD    `diyformid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_order_goods', 'diyformdata')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD    `diyformdata` text NULL;");
}
if(!pdo_fieldexists('sz_yi_order_goods', 'diyformfields')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD    `diyformfields` text NULL;");
}

$info = pdo_fetch('select * from ' . tablename('sz_yi_plugin') . ' where identity= "exhelper"  order by id desc limit 1');

if(!$info){
    $sql = "INSERT INTO " . tablename('sz_yi_plugin'). " (`displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`) VALUES(0, 'exhelper', '快递助手', '1.0', '官方', 1, 'tool');";
    pdo_query($sql);
}

$info = pdo_fetch('select * from ' . tablename('sz_yi_plugin') . ' where identity= "yunpay"  order by id desc limit 1');

if(!$info){
    $sql = "INSERT INTO " . tablename('sz_yi_plugin'). " (`displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`) VALUES(0, 'yunpay', '云支付', '1.0', '云支付', 1, 'tool');";
    pdo_query($sql);
}

$info = pdo_fetch('select * from ' . tablename('sz_yi_plugin') . ' where identity= "supplier"  order by id desc limit 1');

if(!$info){
    $sql = "INSERT INTO " . tablename('sz_yi_plugin'). " (`displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`) VALUES(0, 'supplier', '供应商', '1.0', '官方', 1, 'biz');";
    pdo_query($sql);
}

$info = pdo_fetch('select * from ' . tablename('sz_yi_plugin') . ' where identity= "diyform"  order by id desc limit 1');

if(!$info){
    $sql = "INSERT INTO " . tablename('sz_yi_plugin'). " (`displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`) VALUES(0, 'diyform', '自定义表单', '1.0', '官方', 1, 'help');";
    pdo_query($sql);
}

$info = pdo_fetch('select * from ' . tablename('sz_yi_plugin') . ' where identity= "system"  order by id desc limit 1');

if(!$info){
    $sql = "INSERT INTO " . tablename('sz_yi_plugin'). " (`displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`) VALUES(0, 'system', '系统工具', '1.0', '官方', 1, 'help');";
    pdo_query($sql);
}
else{
    $sql = "update " . tablename('sz_yi_plugin'). " set `name` = '系统工具' where `identity` = 'system';";
    pdo_query($sql);
}

if(!pdo_fieldexists('sz_yi_goods', 'shorttitle')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD  `shorttitle`  VARCHAR( 500 ) DEFAULT NULL;");
}

if(!pdo_fieldexists('sz_yi_goods', 'commission_level_id')) {
	pdo_query("ALTER TABLE ".tablename('sz_yi_goods')." ADD  `commission_level_id`  int(11) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order', 'printstate')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD  `printstate`  tinyint(3) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order', 'printstate2')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_order')." ADD  `printstate2`  tinyint(3) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order_goods', 'printstate')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD  `printstate`  tinyint(3) DEFAULT '0';");
}

if(!pdo_fieldexists('sz_yi_order_goods', 'printstate2')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_order_goods')." ADD  `printstate2`  tinyint(3) DEFAULT '0';");
}

//运费
if(!pdo_fieldexists('sz_yi_dispatch', 'isdefault')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_dispatch')." ADD  `isdefault`  tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_dispatch', 'calculatetype')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_dispatch')." ADD  `calculatetype`  tinyint(1) DEFAULT '0';");
}

//计件
if(!pdo_fieldexists('sz_yi_dispatch', 'firstnumprice')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_dispatch')." ADD  `firstnumprice`  decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('sz_yi_dispatch', 'secondnumprice')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_dispatch')." ADD  `secondnumprice`  decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('sz_yi_dispatch', 'firstnum')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_dispatch')." ADD  `firstnum`  int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sz_yi_dispatch', 'secondnum')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_dispatch')." ADD  `secondnum`  int(11) DEFAULT '0';");
}
//文章营销
if(!pdo_fieldexists('sz_yi_article_sys', 'article_area')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_article_sys')." ADD  `article_area`  TEXT NULL COMMENT '文章阅读地区';");
}
if(!pdo_fieldexists('sz_yi_article', 'article_rule_money_total')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_article')." ADD  `article_rule_money_total`  DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' COMMENT '最高累计奖金' AFTER `article_rule_money`;");
}
if(!pdo_fieldexists('sz_yi_article', 'article_rule_userd_money')) {
    pdo_query("ALTER TABLE ".tablename('sz_yi_article')." ADD  `article_rule_userd_money` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0' COMMENT '截止目前累计奖励金额' AFTER`article_rule_money_total`");
}

