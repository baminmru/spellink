<?php 
	// проверяем, что администратор вошел в систему
require_once('auth2.php');

	// подключаем функции доступа к базе данных 
 require 'database.php';

	// это отсылка данных с формы, иначе просто показать пустую форму для заполнения
	if ( !empty($_POST)) {
		
		
		// keep track validation errors
		$usernameError = null;
		$pwdError = null;
		$isadminError = null;
		
		// keep track post values
		$username = $_POST['username'];
		$pwd = $_POST['pwd'];
		
		if(isset($_POST['chk'])){
			$isadmin = $_POST['chk'];
		}
		else
		{
			$isadmin = 0;
		}
		
		// validate input
		$valid = true;
		if (empty($username)) {
			$usernameError = 'Please enter username';
			$valid = false;
		}
		
		if (empty($pwd)) {
			$pwdError = 'Please enter password';
			$valid = false;
		}
		
		
		
		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// создаем новую запись о пользователе
			$sql = "INSERT INTO logins (id,username,pwd,isadmin) values(uuid(),?, md5(?), ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($username,$pwd,$isadmin));

			Database::disconnect();
			
			// возвращаемся к списку пользователей
			header("Location: logins.php");
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
		    			<h3>Создать пользователя</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="logins_create.php" method="post">
					  <div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
					    <label class="control-label">Логин</label>
					    <div class="controls">
					      	<input name="username" type="text"  placeholder="username" value="<?php echo !empty($username)?$username:'';?>" />
					      	<?php if (!empty($usernameError)): ?>
					      		<span class="help-inline"><?php echo $usernameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					  
					   <div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
					    <label class="control-label">Пароль</label>
					    <div class="controls">
					      	<input name="pwd" type="password"  placeholder="password" value="<?php echo !empty($pwd)?$pwd:'';?>" />
					      	<?php if (!empty($pwdError)): ?>
					      		<span class="help-inline"><?php echo $pwdError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>
					
					  <div class="control-group <?php echo !empty($isadminError)?'error':'';?>">
					    <label class="control-label">Администратор</label>
					    <div class="controls">
					      	<input name="chk" type="checkbox" value="1"   />
					      	
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Создать</button>
						  <a class="btn" href="logins.php">Назад</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>