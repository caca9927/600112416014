<?php
	if(!isset($_SESSION['ses_sysobj'])){
		$obj_ = new sysconnect();
		//$obj_->setConnStr($connection_string);
		$_SESSION['ses_sysobj'] = serialize($obj_);
		echo "new";
	}else{
		$obj_=unserialize($_SESSION['ses_sysobj']);
		echo "old";
	}

?>