<?php 
// проверяем, что администратор вошел в систему
require_once('auth.php');

	$L = '';
	// получаем  идентификатор задачи
	if ( !empty($_GET['L'])) {
		$L = $_REQUEST['L'];
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
<h1><a href="admin.php" >Проверка ссылок и правописания</a></h1>
    <div class="container">
    		<div class="row">
    			<h3>Словарь пользователя</h3>
    		</div>
			<div class="row">
				<p>
					<a href="userdict_create.php" class="btn btn-success">Добавить слово</a>
				</p>
				<div>
				<a href="userdict.php?L=">Все</a>&nbsp;
				<a href="userdict.php?L=А">А</a>&nbsp;
				<a href="userdict.php?L=Б">Б</a>&nbsp;
				<a href="userdict.php?L=В">В</a>&nbsp;
				<a href="userdict.php?L=Г">Г</a>&nbsp;
				<a href="userdict.php?L=Д">Д</a>&nbsp;
				<a href="userdict.php?L=Е">Е</a>&nbsp;
				<a href="userdict.php?L=Ё">Ё</a>&nbsp;
				<a href="userdict.php?L=Ж">Ж</a>&nbsp;
				<a href="userdict.php?L=З">З</a>&nbsp;
				<a href="userdict.php?L=И">И</a>&nbsp;
				<a href="userdict.php?L=Й">Й</a>&nbsp;
				<a href="userdict.php?L=К">К</a>&nbsp;
				<a href="userdict.php?L=Л">Л</a>&nbsp;
				<a href="userdict.php?L=М">М</a>&nbsp;
				<a href="userdict.php?L=Н">Н</a>&nbsp;
				<a href="userdict.php?L=О">О</a>&nbsp;
				<a href="userdict.php?L=П">П</a>&nbsp;
				<a href="userdict.php?L=Р">Р</a>&nbsp;
				<a href="userdict.php?L=С">С</a>&nbsp;
				<a href="userdict.php?L=Т">Т</a>&nbsp;
				<a href="userdict.php?L=У">У</a>&nbsp;
				<a href="userdict.php?L=Ф">Ф</a>&nbsp;
				<a href="userdict.php?L=Х">Х</a>&nbsp;
				<a href="userdict.php?L=Ц">Ц</a>&nbsp;
				<a href="userdict.php?L=Ч">Ч</a>&nbsp;			
				<a href="userdict.php?L=Ш">Ш</a>&nbsp;
				<a href="userdict.php?L=Щ">Щ</a>&nbsp;
				<a href="userdict.php?L=Э">Э</a>&nbsp;
				<a href="userdict.php?L=Ю">Ю</a>&nbsp;
				<a href="userdict.php?L=Я">Я</a>&nbsp;
				
				</div>
				
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>Слово</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   include 'database.php';
					   $pdo = Database::connect();
					   
					   if($L!='' ){
						   $sql = 'SELECT word FROM userdict where word like \''.$L.'%\' ORDER BY word';
					   }else{
						   $sql = 'SELECT word FROM userdict ORDER BY word';
					   }
					    
					   
					   

					   
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
							   	echo '<td>'. $row['word'] . '</td>';
								
								echo '<td>';
								echo '<a class="btn btn-danger" href="userdict_delete.php?w='.$row['word'].'">Удалить</a>';
								
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