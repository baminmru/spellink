<?php 

// проверяем, что пользователь вошел в систему
require_once('auth.php');

	$id = null;
	// получаем  идентификатор задачи
	if ( !empty($_GET['pageid'])) {
		$pageid = $_REQUEST['pageid'];
	}
	
	// получаем  идентификатор задачи
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	

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
    			<h3>Неверные ссылки</h3>
    		</div>
			<div class="row">
				 <a class="btn" href="sites_view.php?id=<?php echo $id; ?>">Назад</a>
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
						  <th>Неверная ссылка</th>
						  <th>Ошибка</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					  
					  // показываем параметры задания на расчет
					   include 'database.php';
					   $pdo = Database::connect();
					   $sql = "SELECT webpage.url,weblink.url errurl,line,pos,error FROM webpage 
						JOIN weblink  ON webpage.pageid=weblink.page_id
						where webpage.pageid= '".$pageid."' order BY line,pos";
					   $res="";
					   $toprow=true;
	 				   foreach ($pdo->query($sql) as $row) {
						   if($toprow){
							   $toprow=false;
							   echo '<tr>';
								echo '<td  colspan=4>';
								echo 'Исходная страница: '.'<a target="_blank" href="'.$row['url'].'">'.$row['url'].'</a>'   ;
								echo '</td>';
							   	echo '</tr>';
						   }
							echo '<tr>';
							echo '<td >';
							echo '<a target="_blank" href="'.$row['errurl'].'">'.$row['errurl'].'</a>'  ;
							echo '</td>';
							echo '<td >';
							echo $row['error']  ;
							echo '</td>';
							echo '</tr>';
					   }
					   echo '</tbody>';
					   echo '</table>';
					   
					   // показываем результат
				      echo '<div style="overflow-y: scroll;">';
					  echo $res;
					  
					  echo '</div>';
					  Database::disconnect();
					  ?>
    	</div>
    </div> <!-- /container -->
  </body>
</html>