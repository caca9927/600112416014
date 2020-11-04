<?php
	if(!isset($_SESSION['ses_sysobj'])){
		$obj_ = new sysconnect();
		$_SESSION['ses_sysobj'] = serialize($obj_);
	}else{
		$obj_=unserialize($_SESSION['ses_sysobj']);
	}
	if(!$obj_->getlogin_stat()){
			$obj_->errRequireAuthMsg();
		exit;
	}
?>