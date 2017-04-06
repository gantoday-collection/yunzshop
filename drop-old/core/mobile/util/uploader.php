<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
load()->func('file');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'upload';
$attribute = $_GPC['attribute'];
if ($op == 'upload') {
	$field = $_GPC['file'];
	if (!empty($_FILES[$field]['name'])) {
		if ($_FILES[$field]['error'] != 0) {
			$result['message'] = '图片上传失败，请重试！';
			exit(json_encode($result));
		}
        $path = '/images/sz_yi/' . $_W['uniacid'];
		if ($attribute == 'member') {
		    $memberid = $_GPC['memberid'];
		    $path = "/images/sz_yi/" . $_W['uniacid'] . "/member";
        }
		if (!is_dir(ATTACHMENT_ROOT . $path)) {
			mkdirs(ATTACHMENT_ROOT . $path);
		}
		$_W['uploadset'] = array();
		$_W['uploadset']['image']['folder'] = $path;
		$_W['uploadset']['image']['extentions'] = $_W['config']['upload']['image']['extentions'];
		$_W['uploadset']['image']['limit'] = $_W['config']['upload']['image']['limit'];
		if ($memberid) {
		    $name = $path . "/member" . $memberid;
            $file = file_upload($_FILES[$field], 'image', $name);
            $data['isactivity'] = "http://" . $_SERVER["HTTP_HOST"] .  "/attachment" . $file['path'];
            pdo_update('sz_yi_member', array('isactivity' => $data['isactivity'] ), array('id' => $memberid));
        } else {
            $file = file_upload($_FILES[$field], 'image');
        }
		if (is_error($file)) {
			$result['message'] = $file['message'];
			exit(json_encode($result));
		}
		if (function_exists('file_remote_upload')) {
			$remote = file_remote_upload($file['path']);
			if (is_error($remote)) {
				$result['message'] = $remote['message'];
				exit(json_encode($result));
			}
		}
		$result['status'] = 'success';
		$result['url'] = $file['url'];
		$result['error'] = 0;
		$result['filename'] = $file['path'];
		$result['url'] = save_media($_W['attachurl'] . $result['filename']);
		pdo_insert('core_attachment', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'filename' => $_FILES[$field]['name'], 'attachment' => $result['filename'], 'type' => 1, 'createtime' => TIMESTAMP,));
		exit(json_encode($result));
	} else {
		$result['message'] = '请选择要上传的图片';
		exit(json_encode($result));
	}
} elseif ($op == 'remove') {
    $file = $_GPC['file'];
    file_delete($file);
    return show_json(1);
}