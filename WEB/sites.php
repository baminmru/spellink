<?php 
// проверяем, что администратор вошел в систему
require_once('auth.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jquery-2.1.1.min.js"></script>
	
	<script>
	function CallAJAX(myurl,msg){
		if(confirm(msg + "?")){
			$("#msg").html("запущен процесс :" +msg +". Ожидайте результата.");
			$.ajax({
				type: "POST",
				url: myurl,
				data: {}
			}
			).done(function( result )
				{
					$("#msg").html("");
					alert( result );
				}
			).fail(function( result )
				{
					$("#msg").html("");
				}
			) ;
		}
	}
	</script>
</head>

<body>
<h1><a href="admin.php" >Проверка ссылок и правописания</a></h1>
    <div class="container">
    		<div class="row">
    			<h3>Сайты</h3>
    		</div>
			<div class="row">
				<p>
					<a href="sites_create.php" class="btn btn-success">Создать</a><div><span id="msg"></span></div>
				</p>
				
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>URL</th>
						  <th>Проверка включена</th>
						  <th>Время последней проверки</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   include 'database.php';
					   $pdo = Database::connect();
					   
					    // заполняем таблицу материалов
					   $sql = 'SELECT * FROM website ORDER BY url DESC';
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
							   	echo '<td>'. $row['url'] . '</td>';
								echo '<td>';
								if($row['allowcheck']==1) 
									echo 'да';
								else
									echo 'Нет';
							   	echo '</td>';
								echo '<td>'. $row['lastcheck'] . '</td>';
								
								
								// кнопочки
							   	echo '<td width=200>';
							   	echo '<a class="btn btn-success" href="sites_update.php?id='.$row['id'].'">Изменить</a>';
								echo '<a class="btn btn-success"  onclick="CallAJAX(\'sites_links.php?id='.$row['id'].'\',\'Проверить ссылки\')">Проверить ссылки</a>';
								echo '<a class="btn btn-success" onclick="CallAJAX(\'sites_spell.php?id='.$row['id'].'\',\'Проверить правописание\')">Проверить правописание</a>';
							   	echo '<br/><a class="btn btn-success" href="sites_view.php?id='.$row['id'].'">Страницы</a>';
								echo '<br/><a class="btn btn-danger" href="sites_delete.php?id='.$row['id'].'">Удалить</a>';
								
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