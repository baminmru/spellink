<?php
error_reporting(E_ERROR & E_PARSE);

// Include the class definition file.
require_once(dirname(__FILE__)  . '/html2text.php');

include_once(dirname(__FILE__)  . '/' .'config.php');

// connect to DB
        $db = new mysqli($config['db']['server'], $config['db']['username'], $config['db']['password'],
                               $config['db']['database']);
							   
	   
		if ($db->connect_error) {
            throw new Exception('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        }
        $db->set_charset("utf8");
		
		
		$result = array();
       
        $res = $db->query('SELECT url,pageid FROM webpage WHERE ignorespell=0');
        while ($row = $res->fetch_assoc()){
            $result[] = $row;
			
        }
        $res->close();
		
		$delimiters= " \r\n\t\'" ; //()_+-=.,[]{}*&^:;%$#@!<>~`/\\|\'\"?";
		
		 
		for($i=0;$i<count($result);$i++){
			
			$stmt = $db->prepare('UPDATE webpage SET spelltime=now() WHERE pageid = ?');
            $stmt->bind_param('s', $result[$i]['pageid']);
            $stmt->execute();
            $stmt->close();
			echo "# ".$result[$i]['url']." start\r\n";
			$pagedata=file_get_contents($result[$i]['url']);
			
			//try{
				$pagedata2 = convert_html_to_text($pagedata);
			//}catch (Exception $ex) {
				$pagedata2 = myHTML2TEXT($pagedata2);
			//}
			
			// все кривое стараемся превратить в пробелы
			//$pagedata2 = str_replace(
			//			array("•","«","»","—","–","№","″","″","©"," ","…","(",")","_","+","-","=",".",",","[","]","{","}","*","&","^",":",";","%","$","#","@","!","<",">","~","`","/","\\","|","\'","\"","?"),
			//			" "
			//		, $pagedata2); 
			
			// убираем лишние пробелы
			//$pagedata2= preg_replace('/\s\s+/', ' ' , $pagedata2);		
			
			
			
			$sp_err="";
			$words= array();
			
			$token=strtok($pagedata2,$delimiters);
			
			
			// погоняем данные страницы и собираем уникальные слова для проверки
			while($token !==false){
				if(strlen($token)>0){
					$token = preg_replace("@\W@u", "", $token); 
					$token = preg_replace("@\d@u", "", $token); 
					$token = preg_replace("/[a-z]/i", "", $token);
					
					$token = str_replace(
						array("\r","\n","\t","•","«","»","—","–","№","″","″","©"," ","…","(",")","_","+","-","=",".",",","[","]","{","}","*","&","^",":",";","%","$","#","@","!","<",">","~","`","/","\\","|","\'","\"","?"),
						""
					, $token); 
					
					// убираем абревиатуры из одних больших букв
					if(!preg_match('/^[А-Я|Ё]+$/u',$token)){
						if(strlen($token)>2){
							if(!in_array($token,$words)){
								$words[]=$token;
							}
						}
					}
				}
				$token=strtok($delimiters);
			}
			
			
			
			$ew=0;
			
			// проверяем собранные слова
			for($j=0;$j<count($words);$j++){
				if(strlen($words[$j])>0){
				 //echo "test: ".$words[$j];	
				 $res = $db->query("SELECT word FROM dict WHERE  word = '".$words[$j]."'");
				 if($res){
					if ($row = $res->fetch_assoc()){
						//echo " -OK\r\n";
					}else{
						
						$res2 = $db->query("SELECT word FROM userdict WHERE  word = '".$words[$j]."'");
						
						if($res2){
							if ($row2 = $res2->fetch_assoc()){
								//echo " -OK\r\n";
							}else{
								//echo " -ERR\r\n";
								// выводм только ошибочные
								$ew+=1;
								echo $words[$j]."\r\n";
								$sp_err=$sp_err.$words[$j]."\r\n";
							}
							$res2->close();
						}
						
						
						
						
					}
					$res->close();
				 }else{
					 //echo "Error: ".$words[$j]."\r\n";
				 }
				}
			}
			
			// на сколько упаковалась информация
			echo "# ".$result[$i]['url']." end : ".strlen( $pagedata)." -> ".strlen($pagedata2)."; w=".count($words). "; e=".$ew."\r\n";
			
			
			// записываем данные для каждой страницы	
			if($sp_err==""){
				
				 // нет  слов, которые не поняли
				$stmt = $db->prepare("UPDATE webpage SET spellresult='OK' WHERE pageid = ?");
				$stmt->bind_param('s', $result[$i]['pageid']);
				$stmt->execute();
				$stmt->close();
			}else{
				
				// есть слова не прошедшие проверку
				$stmt = $db->prepare('UPDATE webpage SET spellresult=? WHERE pageid = ?');
				$stmt->bind_param('ss', $sp_err, $result[$i]['pageid']);
				$stmt->execute();
				$stmt->close();
			}
			
		}
		
		
		$db->close();
		//	echo("page speller finished.\r\n");
		
		
		// жестко тупой вариант 
		function myHTML2TEXT($Document) {
		$Rules = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
						'@<style[^>]*?>.*?</style>@si',   // Strip out style
						'@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
						'@([\r\n])[\s]+@',                // Strip out white space
						'@&(quot|#34);@i',                // Replace HTML entities
						'@&(amp|#38);@i',                 //   Ampersand &
						'@&(lt|#60);@i',                  //   Less Than <
						'@&(gt|#62);@i',                  //   Greater Than >
						'@&(nbsp|#160);@i',               //   Non Breaking Space
						'@&(iexcl|#161);@i',              //   Inverted Exclamation point
						'@&(cent|#162);@i',               //   Cent
						'@&(pound|#163);@i',              //   Pound
						'@&(copy|#169);@i',               //   Copyright
						'@&(reg|#174);@i',                //   Registered
						'@\W@u',						  //   убрать все не буквы и не цифры	
						'/\s\s+/'						  //   Убрать лишние пробулы	
						);                   
		
	return preg_replace($Rules, " ", $Document);
}


		
		
							   
?>