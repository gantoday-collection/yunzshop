<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once("alipay_build/alipay.config.php");
require_once("alipay_build/lib/alipay_notify.class.php");
require '../../../../framework/bootstrap.inc.php';
require '../../../../addons/sz_yi/defines.php';
require '../../../../addons/sz_yi/core/inc/functions.php';
require '../../../../addons/sz_yi/core/inc/plugin/plugin_model.php';
$_W['uniacid'] = $_GET['uniacid'];
$setting = uni_setting($_W['uniacid'], array('payment'));
if (is_array($setting['payment'])) {
    $options = $setting['payment']['alipay'];
    if(!empty($options)){
        $partner = $options['partner'];
        $secret = $options['secret'];
    }else{
        $partner = '';
        $secret = '';
    }
}
$alipay_config['partner'] = $partner;
$alipay_config['key'] =  $secret;
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
	 require '../../../../framework/bootstrap.inc.php';
	 require '../../../../addons/sz_yi/defines.php';
	 require '../../../../addons/sz_yi/core/inc/functions.php';
	 require '../../../../addons/sz_yi/core/inc/plugin/plugin_model.php';
 	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//批量付款数据中转账成功的详细信息

	$success_details = $_POST['success_details']; //成功信息
	$batch_no = $_POST['batch_no']; //批次号
	if($success_details!=''){
		$log= array('status'=>'1');
 	    pdo_update('sz_yi_member_log', $log, array('batch_no' =>$batch_no));
	}

	//批量付款数据中转账失败的详细信息
	$fail_details = $_POST['fail_details']; //失败信息

    if($fail_details!=''){   	
		$log= array('status'=>'2');
 	    pdo_update('sz_yi_member_log', $log, array('batch_no' =>$batch_no));
    }

	//判断是否在商户网站中已经做过了这次通知返回的处理
		//如果没有做过处理，那么执行商户的业务程序
		//如果有做过处理，那么不执行商户的业务程序
        
	echo "success";		//请不要修改或删除

	//调试用，写文本函数记录程序运行情况是否正常
	//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {

    //验证失败
     echo "fail";  
    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>