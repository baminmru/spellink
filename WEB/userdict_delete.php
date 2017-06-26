<?php 
// проверяем, что администратор вошел в систему
require_once('auth.php');

	// подключаем функции доступа к базе данных 
 require 'database.php';
	
	if ( !empty($_GET['w'])) {
		$id = $_REQUEST['w'];
		
		// удаляем запись
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM userdict WHERE word =?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		// возвращаемся к словарю
		
		
	} 
	header("Location: userdict.php");
