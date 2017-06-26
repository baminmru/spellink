<?php 
// проверяем, что пользователь вошел в систему
require_once('auth.php');

	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(isset($_SESSION['SESS_MEMBER_ID']) && (trim($_SESSION['SESS_MEMBER_ID']) != '')) {
		
		// перекидываем на страницу администратора, она сам решит, что показывать
		header("location: admin.php");
		exit();
	}
