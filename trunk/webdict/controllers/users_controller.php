<?php

class UsersController extends AppController {
	var $name = 'Users';
    var $uses = array('Dict');

	private function setMessage($message) {
	$this->set('message', $message);
    }
	
	function register($mess) {
		$this->layout = null;
		$dicts = $this->Dict->query("select * from dictionaries");
		$this->set("dicts", $dicts);
		if (isset($_POST['submit'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$gender = $_POST['gender'];
			
			$check = $this->Dict->query("select * from users where username='".$username."'");
			if (!empty($check)) {
				$this->redirect("/users/register/1");
			} else {
				$this->Dict->query("insert into users values(NULL, '".$username."', '".$password."', $gender)");
				$this->redirect("/users/login");
			}
		}
		
		if ($mess==1) $this->set("mess", "Email này đã có người đăng ký!");
	}
	
	function login() {
		$this->layout = null;
		$dicts = $this->Dict->query("select * from dictionaries");
		$this->set("dicts", $dicts);
		
		if (isset($_POST['submit'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$result = $this->Dict->query("select * from users where username='".$username."' and password='".$password."'");
			//print_r($result);
			if (!empty($result)) {
				$this->username = $result[0]['users']['username'];
				$this->user_id = $result[0]['users']['id'];
				$_SESSION['username'] = $this->username;
				$_SESSION['user_id'] = $this->user_id;				
				//echo $this->user_id;
				$this->redirect("/");
			}
		}
		
		$this->set('user_id', $_SESSION['user_id']);
		$this->set('username', $_SESSION['username']);
	}
	
	function logout() {
		$_SESSION['username'] = '';
		$this->redirect("/");
	}
	
	function addword($id = '') {
		$this->layout = null;
		if ($id != '') {
			if ($_SESSION['user_id']!='') {
				$this->Dict->query("insert into user_words values(NULL, ".$_SESSION['user_id'].", $id)");
				$this->set("status", 1);
			} else {
				echo "<script type='text/javascript'>alert('Vui lòng đăng nhập!');</script>";
				$this->set("status", 0);
			}
		}
	}
	
	function logino()
	{
		$this->layout = null;
		$returnTo = 'http://'.$_SERVER['SERVER_NAME'].'/users/login';

		if (!empty($this->data)) {
			try {
			$this->Openid->authenticate($this->data['OpenidUrl']['openid'], $returnTo, 'http://'.$_SERVER['SERVER_NAME']);
			} catch (InvalidArgumentException $e) {
			$this->setMessage('Invalid OpenID');
			} catch (Exception $e) {
			$this->setMessage($e->getMessage());
			}
		} elseif ($this->Openid->isOpenIDResponse()) {
			$response = $this->Openid->getResponse($returnTo);

			if ($response->status == Auth_OpenID_CANCEL) {
			$this->setMessage('Verification cancelled');
			} elseif ($response->status == Auth_OpenID_FAILURE) {
			$this->setMessage('OpenID verification failed: '.$response->message);
			} elseif ($response->status == Auth_OpenID_SUCCESS) {
			echo 'successfully authenticated!';
			exit;
			}
		}

	}
	
}

?>