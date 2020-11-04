<?php

class sysconnect{
	private $host="localhost:3306";
	private $dbname="webdb";
	private $user="root";
	private $password="";
	private $user_id;
	private $user_fullname;
	private $login_stat = false;
	private $date_time;
	private $timeout = 180;
	public $g_val;
	private $email;
	private $alert_off = false;

    function __construct(){$this->date_time = date('Y-m-d h:i:s');}
	//public function __sleep(){return;}
	public function __wakeup(){
		if($this->getlogin_stat()){
			if($this->getTimeOutLogin()) {
				//$this->setAlertOff(true);
				$this->user_fullname="";
				$this->setlogin_stat(false,'unknown');
				$this->setSessionObj($this);
				unset($_SESSION['ses_sysobj']);
				session_destroy();

				//header("Location: http://localhost/sach/v2/mgr/sys_cgi/sys_logout.php");


				echo "<style type=\"text/css\" media=\"all\">.alert-login{display:block; padding-top: 100px;font-size: 14px;text-align: center;}.alert-login .cap{font-size: 18px;}</style>";
				echo "<p><div class=\"alert-login\"><div class=\"cap\">กรุณายืนยันว่าเป็นคุณ เพื่อความปลอดภัย!!</div></p>";
				echo "<a style=\"width: 210px; border-radius: 2px;font-size: 18px; text-decoration: none; padding: 10px 0; display: inline-block; margin: 0 13px;text-align: center;transition: background-color .3s ease;cursor: pointer;background-color:  #0078d7;border: 2px solid #0078d7;color: #fff;\" href=\"./login.html\" >ลงชื่อเข้าระบบใหม่อีกครั้ง</a>";
				exit;

			}else {
				$this->date_time = date('Y-m-d h:i:s');
				$this->setSessionObj($this);
			}
		}else{
			$this->setAlertOff(false);
			$this->setSessionObj($this);
		}
	}

	public function conn_(){
		$conn = mysqli_connect($this->host, $this->user, $this->password, $this->dbname);
		return $conn;
    }
	public function setAlertOff($val){
		$this->alert_off = $val;
	}
	public function getAlertOff(){
		return($this->alert_off);
	}
	public function setEmail($str){$this->email = $str;}

	public function setuser_id($val){
		$this->user_id = $val;
	}
	public function setlogin_stat($val,$userid){
		$this->login_stat = $val;
		$this->user_id = $userid;
		$this->date_time = date('Y-m-d h:i:s');
	}
	public function getuser_id(){
		return $this->user_id;
	}
	public  function getuser_fullname(){
		return $this->user_fullname;
	}
	public function getlogin_stat(){
		return $this->login_stat;
	}
	public function set_gval($val){
		if(strlen($val)<=0)
			$this->g_val = "";
			else
				$this->g_val = $val;

	}


	public function get_gval(){
		return $this->g_val;
	}

	public function getEmail(){return $this->email;}
    


	public function getTimeStamp(){return $this->date_time;}
	function println(){
		echo "\n\nTest";
	}

	private function getTimeOutLogin(){
		 $tmp_time = (new DateTime($this->date_time))->getTimestamp();
		 $tmp_time2 = (new DateTime(date('Y-m-d h:i:s')))->getTimestamp();
		 if(($tmp_time2 - $tmp_time) >= $this->timeout){
			 return true;
		 }else{
			 return false;
		 }
	}
	private function setTimeLogin(){
		$this->date_time = date('Y-m-d h:i:s');
	}


	function setSessionObj($obj){
		$_SESSION['ses_sysobj'] = serialize($obj);
		
	}


	public function valid_user($conn_,$str){
		$uid=$str['userid'];;
		$pw=trim($this->pwdHash($uid,$str['password']));
		$sql = "select userid,password,fullname,email from user  where userid = '$uid' and password = '$pw'";
		$result = mysqli_query($conn_, $sql);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$this->user_fullname = $row['fullname'];
			$this->email = $row['email'];
			$this->setlogin_stat(true,$row['userid']);
		}else{
			$this->user_fullname="";
			$this->setlogin_stat(false,"unknown");

		}

	}


function pwdHash($salt, $pwd){
    $off = ord($salt) % 17;
    $salt = md5($salt);
    $crypt = substr($salt, 0, $off);
    $i = -1;
    while(isset($pwd[++$i]))
    {
        $crypt .= $pwd[$i];
        $crypt .= $salt[$i + $off];
    }
    $crypt = $crypt . substr($salt, $i + $off);
    return $crypt;
    hash('sha256', $crypt);
}




function errRequireAuthMsg(){
	echo "<div style=\"width:100%;text-align:center;\">";
		echo "<div style=\"padding:100px 0px 20px 0px;\">คุณยังไม่ลงทะเบียนเข้าระบบ</div>";
		echo "<div >";
			echo "<a style=\"width: 210px; border-radius: 2px;font-size: 18px; text-decoration: none; padding: 10px 0; display: inline-block; margin: 0 13px;text-align: center;transition: background-color .3s ease;cursor: pointer;background-color:  #0078d7;border: 2px solid #0078d7;color: #fff;\" href=\"./login.html\" >ลงชื่อเข้าสู่ระบบ</a>";
		echo "</div>";
	echo "</div>";

}

}//end class

?>
