<?php
	//Start session
	session_start();
	
	//проверка, что пользователь уже  вошел, иначе переход к форме регистрации
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: login-form.php");
		exit();
	}
?>