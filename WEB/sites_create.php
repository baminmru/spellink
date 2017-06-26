<?php 
	// проверяем, что администратор вошел в систему
require_once('auth2.php');

	// подключаем функции доступа к базе данных 
 require 'database.php';
 
	$allowcheck = 0;
	$url="";

	// это отсылка данных с формы, иначе просто показать пустую форму для заполнения
	if ( !empty($_POST)) {
		$url=$_POST['url'] ;
		
		if(isset($_POST['allowcheck'])){
			$allowcheck = $_POST['allowcheck'];
		}
		else
		{
			$allowcheck = 0;
		}
			
		// validate input
		$valid = true;
		if (empty($url)) {
			$valid = false;
		}
		
		if (empty($allowcheck)) {
			$allowcheck = 0;
		}
		
		
		
		
		
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// создаем новую запись о сайте
			$sql = "INSERT INTO website (url ,allowcheck ) values( ? ,? )";
			$q = $pdo->prepare($sql);
			$q->execute(array($url ,$allowcheck));

			Database::disconnect();
			// возвращаемся к списку  сайтов
			header("Location: sites.php");
		}
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
		    			<h3>Создать сайт</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="sites_create.php" method="post">
				
					
					  <div class="control-group ">
					    <label class="control-label">Сайт</label>
					    <div class="controls">
					      	<input name="url" type="text"   value="<?php echo !empty($url)?$url:'';?>" />
					    </div>
					  </div>
					  
					  
					  <div class="control-group ">
					    <label class="control-label">Проверка включена</label>
						<div class="controls">
					      	<input name="allowcheck" type="checkbox" value="1" <?php echo $allowcheck=='1'?'checked':'';?>  />
					      	
					    </div>
					  </div>
					  
					 
					  
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Создать</button>
						  <a class="btn" href="sites.php">Назад</a>
					   </div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>