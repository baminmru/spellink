<?php
	//Start session
	session_start();
	
	////проверка, что администратор уже  вошел, иначе переход к форме для пользователя
	if(!isset($_SESSION['SESS_MEMBER_ADMIN']) || (trim($_SESSION['SESS_MEMBER_ADMIN']) != 1)) {
		header("location: user.php");
		exit();
	}
?>