<?php 

	// проверяем, что пользователь вошел в систему
	require_once('auth.php');

	$id = null;
	// получаем  идентификатор задачи
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>

</script>
	<script>
	function AddWord(newword){
		$("#msg").html("Добавляем в словарь: "+ newword +".");
		$.ajax({
			type: "POST",
			url: "addword.php?w=" +newword,
			data: {}
		}).done(function( result )
			{
				$("#msg").html(newword +" добавлено");
				//alert( newword +" добавлено");
			}
		).fail(function( result )
			{
				$("#msg").html("Ошибка добавления");
			}
		) ;
	}
	</script>
</head>

<body>
<h1><a href="admin.php" >Проверка ссылок и правописания</a></h1>
    <div class="container">
    		<div class="row">
    			<h3>Страницы сайта</h3>
    		</div>
			<div class="row">
				 <a class="btn" href="sites.php">Назад</a>
				 <div class="row">
					<span  id="msg"></span>
				</div>
			<table class="table table-striped table-bordered" id="tab_info" >
		              <thead>
		                <tr>
						  <th>Страница</th>
						  <th>Слова не прошедшие проверку</th>
						  <th>Количество неверных ссылок</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					  
					  // показываем параметры задания на расчет
					   include 'database.php';
					   $pdo = Database::connect();
					   $sql = "SELECT webpage.pageid,webpage.url,webpage.spellresult, COUNT(link_id) badlinks FROM webpage 
						left JOIN weblink  ON webpage.pageid=weblink.page_id
						where webpage.site_id= '".$id."' group BY webpage.url,webpage.pageid,webpage.spellresult";
					   $res="";
	 				   foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td><a target="_blank" href="'. $row['url'] .'">'. $row['url'] .'</a></td>';
							echo '<td>';
							if($row['spellresult']!='OK'){
								echo '<a class="btn btn-success"  href="page_spell.php?pageid='.$row['pageid'].'&id='.$id.'">Перепроверить</a><br/>';
								
								$ew=explode("\n",$row['spellresult']);
								
								foreach($ew as $word){
									$word=str_replace(array("\r","\n"."\t"),"",$word);
									if ($word !=''){
										foreach ($pdo->query("SELECT COUNT(word) CNT FROM userdict WHERE  word = '".$word."'") as $row2) {
											if($row2['CNT']==0){
												echo '<br/>';
												echo '<a class="btn btn-success" onClick = "AddWord(\''.$word.'\')" >+</a>&nbsp;';
												echo $word ;
												
											}else{
												echo '<br/>';
												echo '&nbsp;&nbsp;[+]&nbsp;&nbsp;&nbsp;';
												echo $word ;
											}
											break;
										}
									
									
									
										
									}
								}
							
							
							}
							echo '</td>';
							echo '<td> ';
							
							if ($row['badlinks'] >0) {
								echo '<a class="btn btn-success" href="sites_badlinks.php?pageid='.$row['pageid'].'&id='.$id.'">Показать</a>&nbsp;';
								echo $row['badlinks'];
							}
							echo '</td>';
							
							echo '</tr>';
					   }
					   echo '</tbody>';
					   echo '</table>';
					   
					
					  Database::disconnect();
					  ?>
    	</div>
    </div> <!-- /container -->
  </body>
</html>