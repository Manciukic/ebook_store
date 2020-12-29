<?php
	
	function setSession($email, $userId){
		$_SESSION['user_id'] = $userId;
		$_SESSION['user_email'] = $email;
	}
/*
	function isLogged(){		//
		if(isset($_SESSION['user_id']))
			return $_SESSION['user_id'];
		else
			return false;
	}*/

?>
