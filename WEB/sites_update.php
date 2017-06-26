<?php 
// проверяем, что администратор вошел в систему
require_once('auth2.php');
?>
<?php 
	
	// подключаем функции доступа к базе данных 
 require 'database.php';

	$id = null;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( null==$id ) {
		header("Location: sites.php");
	}
	
	// если посылка с формы, то проверяем параметры и обновляем базу
	if ( !empty($_POST)) {
	
		$allowcheck = 0;
		$url=$_POST['url'] ;
		
		if(isset($_POST['chk'])){
			$allowcheck = 1;
		}
		
		// validate input
		$valid = true;
		if (empty($url)) {
			$valid = false;
		}

		
		// обновляе данные в базе
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "update website set url=? ,allowcheck=".$allowcheck."  where id=?";
			$q = $pdo->prepare($sql);
			$q->execute(array($url ,$id));

			Database::disconnect();
			// возвращаемся к списку сайтов
			header("Location: sites.php");
			//echo $url," ",$allowcheck," ",$id;
		}
	} else {
		//  при первом запуске читаем  данные из базы и показываем форму
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM website where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$url=$data['url'] ;
		$allowcheck=$data['allowcheck'];
		Database::disconnect();
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
		    			<h3>Изменить данные сайта</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="sites_update.php?id=<?php echo $id?>" method="post">
				
					
					  <div class="control-group ">
					    <label class="control-label">Сайт</label>
					    <div class="controls">
					      	<input name="url" type="text"   value="<?php echo !empty($url)?$url:'';?>" />
					    </div>
					  </div>
					  
					  
					  <div class="control-group ">
					    <label class="control-label">Проверка включена</label>
						<div class="controls">
					      	<input name="chk" type="checkbox" value="1" <?php echo $allowcheck=='1'?'checked':'';?>  />
					      	
					    </div>
					  </div>
					  
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Изменить</button>
						  <a class="btn" href="sites.php">Назад</a>
					   </div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>