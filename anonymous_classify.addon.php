<?php
    $first = array("노래하는", "책읽는", "익명의", "구르는", "기여하는", "반달하는", "위키하는", "앉아있는", "날아가는", "놀고있는", "잉여한", "글쓰는", "뛰고있는", "자고있는", "죽은", "살아있는", "학생", "편집하는", "놀러가는", "수영하는", "게임하는", "손짓하는", "구경하는", "눈팅중인");
    $second = array("리브라", "리디버그", "리브렌", "위키러", "위키니트", "위키냥", "리버티", "리버덕", "리돌이", "륙", "에르빌", "리브레비", "리브리", "리브봇", "프라하", "리브맨", "반달가위", "리브크레", "세피로트", "무냐");
    if(!defined("__XE__")) exit();

	if($called_position == 'after_module_proc' && $this->act == "procBoardInsertDocument") {
		$module = Context::get('module');
		if(!$module) $module = $this->module;
		if($module != 'board') return;

		if($this->module_info->use_anonymous == 'Y') {
				$logged_info = Context::get('logged_info');

				$args->document_srl = $this->get('document_srl');
				if(isset($logged_info)) {
					$first_rand = hexdec(substr(md5($args->document_srl.$addon_info->security_code.$logged_info->member_srl),0,6)) % count($first);
					$second_rand = hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) % count($second);
					
					$args->nick_name = $first[$first_rand]. " " . $second[$second_rand];
					//$args->nick_name = "리브러_".substr(md5($args->document_srl.$addon_info->security_code.$logged_info->member_srl),0,6);
				} else {
					$first_rand = hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) % count($first);
					$second_rand = (hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) * 3) % count($second);
					
					$args->nick_name = $first[$first_rand]. " " . $second[$second_rand];
					//$args->nick_name = "리브러_".substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6);
				}
				executeQuery('addons.anonymous_classify.updatedocuments', $args);
		}
	}

	if($called_position == 'after_module_proc' && $this->act == "procBoardInsertComment") {
		if($this->module_info->use_anonymous == 'Y') {
				$args->document_srl = Context::get('document_srl');
				$args->comment_srl = $this->get('comment_srl');

				$oDocumentModel = &getModel('document');
				$oDocument = $oDocumentModel->getDocument($args->document_srl);
				$oget_member_srl = $oDocument->get('member_srl');

				$logged_info = Context::get('logged_info');
				$check = $oget_member_srl != -1*$logged_info->member_srl ? true : false;

				if(!$logged_info->member_srl && !$oget_member_srl) {
					$logged_info->member_srl = $_SERVER['REMOTE_ADDR']; 
					$oget_member_srl = $oDocument->get('ipaddress');

					$check = $oget_member_srl == $logged_info->member_srl ? false : true;
				}

				if($check) {
					if(isset($logged_info)) {
						$first_rand = hexdec(substr(md5($args->document_srl.$addon_info->security_code.$logged_info->member_srl),0,6)) % count($first);
						$second_rand = hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) % count($second);

						$args->nick_name = $first[$first_rand]. " " . $second[$second_rand] . "_" . (hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) % 100);
						#$args->nick_name = "리브러_".substr(md5($args->document_srl.$addon_info->security_code.$logged_info->member_srl),0,6);
					} else {
						$first_rand = hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) % count($first);
						$second_rand = (hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) * 3) % count($second);
						
						$args->nick_name = $first[$first_rand]. " " . $second[$second_rand] . "_" . (hexdec(substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6)) % 100);
						#$args->nick_name = "리브러_".substr(md5($args->document_srl.$addon_info->security_code.$_SERVER['REMOTE_ADDR']),0,6);
					}
				} else {$args->nick_name = "익명의 리브러"; }
				executeQuery('addons.anonymous_classify.updatecomments', $args);
		}
	}
?>
