<?php 
// проверяем, что пользователь вошел в систему
require_once('auth.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
<h1>Проверка ссылок и правописания</h1>
    <div class="container">
    		<div class="row">
    			<h3>Управление</h3>
    		</div>
			<div class="row">
				<p>
					<a href="sites.php" class="btn btn-success">Сайты для проверки</a>
				</p>
				<p>
					<a href="userdict.php" class="btn btn-success">Словарь</a>
				</p>
				<p>
					<a href="logout.php" class="btn btn-danger">Выход</a>
				</p> 
				
				
    	</div>
    </div>
</html>