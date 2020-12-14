<?php
	
	function setSession($username, $userId){
		$_SESSION['user_id'] = $userId;
		$_SESSION['username'] = $username;
	}
/*
	function isLogged(){		//
		if(isset($_SESSION['user_id']))
			return $_SESSION['user_id'];
		else
			return false;
	}*/

?>
