<?php 
// проверяем, что администратор вошел в систему
require_once('auth2.php');
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
<h1><a href="admin.php" >Проверка ссылок и правописания</a></h1>
    <div class="container">
    		<div class="row">
    			<h3>Пользователи</h3>
    		</div>
			<div class="row">
				<p>
					<a href="logins_create.php" class="btn btn-success">Создать</a>
				</p>
				
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>Имя</th>
						  <th>Роль</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   include 'database.php';
					   $pdo = Database::connect();
					   
					   // заполняем таблицу пользователей
					   $sql = 'SELECT logins.id, logins.username,logins.isadmin FROM logins ORDER BY username DESC';
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
							   	echo '<td>'. $row['username'] . '</td>';
								echo '<td>';
								if($row['isadmin']==1) 
									echo 'Администратор';
								else
									echo 'Пользователь';
								echo '</td>';
								
								
							   	echo '<td width=250>';
								// кнопки для управления записью
							   	echo '<a class="btn btn-success" href="logins_update.php?id='.$row['id'].'">Изменить</a>';
								if($row['username']!='admin' && $row['id']!=$_SESSION['SESS_MEMBER_ID']){
									echo '&nbsp;';
									echo '<a class="btn btn-danger" href="logins_delete.php?id='.$row['id'].'">Удалить</a>';
								}
							   	echo '</td>';
							   	echo '</tr>';
					   }
					   Database::disconnect();
					  ?>
				      </tbody>
	            </table>
    	</div>
    </div> <!-- /container -->
  </body>
</html>