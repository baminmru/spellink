<?php 
	// проверяем, что администратор вошел в систему
require_once('auth.php');

	// подключаем функции доступа к базе данных 
 require 'database.php';

	// это отсылка данных с формы, иначе просто показать пустую форму для заполнения
	if ( !empty($_POST)) {
			$word=$_POST['word'] ;
			
		
		// validate input
		$valid = true;
		if (empty($word)) {
			$valid = false;
		}
		
		
		
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// создаем новую запись о резце
			$sql = "INSERT INTO userdict (word ) values( ? )";
			$q = $pdo->prepare($sql);
			$q->execute(array($word ));

			Database::disconnect();
			// возвращаемся к списку резцов
			header("Location: userdict.php");
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
		    			<h3>Добавляем в словарь</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="userdict_create.php" method="post">
				
					
					  <div class="control-group ">
					    <label class="control-label">Слово</label>
					    <div class="controls">
					      	<input name="word" type="text"   value="<?php echo !empty($word)?$word:'';?>" />
					    </div>
					  </div>
	
					  
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Создать</button>
						  <a class="btn" href="userdict.php">Назад</a>
					   </div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>