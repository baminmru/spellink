<?php
	// проверяем, что администратор вошел в систему
require_once('auth2.php');

	// подключаем функции доступа к базе данных 
 require 'database.php';
 
	if ( !empty($_GET['w'])) {
		$word = $_REQUEST['w'];
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// создаем новую запись о пользователе
		$sql = "insert into userdict(word) values(?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($word));
		Database::disconnect();
		//echo("Слово: ".$word. " добавлено в словарь.\r\n");
	}
?>