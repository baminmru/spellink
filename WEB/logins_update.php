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
		header("Location: logins.php");
	}
	
	// если посылка с формы, то проверяем параметры и обновляем базу
	if ( !empty($_POST)) {
			// keep track validation errors
		
		$pwdError = null;
		$isadminError = null;
		
		// keep track post values
		
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
		
		
		if (empty($pwd)) {
			$pwdError = 'Please enter password';
			$valid = false;
		}
		
		// обновляе данные в базе
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE logins  set pwd = md5(?), isadmin =? WHERE id = ?";
			$q = $pdo->prepare($sql);
			$q->execute(array($pwd,$isadmin,$id));
			Database::disconnect();
			
			// возвращаемся к списку пользователей
			header("Location: logins.php");
		}
	} else {
		//  при первом запуске читаем  данные из базы и показываем форму
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT username,isadmin FROM logins where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		$username = $data['username'];
		$isadmin = $data['isadmin'];
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
		    			<h3>Изменить данные для входа</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="logins_update.php?id=<?php echo $id?>" method="post">
					  <div class="control-group <?php echo !empty($domainError)?'error':'';?>">
					    <div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
					    <label class="control-label">Логин: <?php echo !empty($username)?$username:'';?></label>
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
					      	<input name="chk" type="checkbox" value="1"  <?php echo $isadmin=='1'?'checked':'';?>  />
					    </div>
					  </div>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Изменить</button>
						  <a class="btn" href="logins.php">Назад</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>