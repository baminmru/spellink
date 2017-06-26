<?php 
// проверяем, что администратор вошел в систему
require_once('auth2.php');

	// подключаем функции доступа к базе данных 
 require 'database.php';
	$id = 0;
	
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	// удаление подтверждено, иначе просто показваем форму для подтверждения удаления
	if ( !empty($_POST)) {
		// keep track post values
		$id = $_POST['id'];
		
		// удаляем запись
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM logins  WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		Database::disconnect();
		
		// возвращаемся к списку пользователей
		header("Location: logins.php");
		
	} 
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Удалить логин</h3>
		    		</div>
		    		
	    			<form class="form-horizontal" action="logins_delete.php" method="post">
	    			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
					  <p class="alert alert-error">Уверены что хотите удалить пользователя ?</p>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-danger">Да</button>
						  <a class="btn" href="logins.php">Нет</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>