<?php

// Include the class definition file.
require_once(dirname(__FILE__)  . '/' .'class.html2text.inc');



include_once(dirname(__FILE__)  . '/' .'config.php');

// connect to DB
        $db = new mysqli($config['db']['server'], $config['db']['username'], $config['db']['password'],
                               $config['db']['database']);
							   
	   
		if ($db->connect_error) {
            throw new Exception('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        }
        $db->set_charset("utf8");
		
		
		$result = array();
       
        $res = $db->query('SELECT url,pageid FROM webpage WHERE  ignorespell=0');
        while ($row = $res->fetch_assoc()){
            $result[] = $row;
			
        }
        $res->close();
		
		
		 
		for($i=0;$i<count($result);$i++){
			
			$stmt = $db->prepare('UPDATE webpage SET spelltime=now() WHERE pageid = ?');
            $stmt->bind_param('s', $result[$i]['pageid']);
            $stmt->execute();
            $stmt->close();
			echo $result[$i]['url']."\r\n";
			$pagedata=file_get_contents($result[$i]['url']);
			
			
			$h2t = new html2text($pagedata);

			// Simply call the get_text() method for the class to convert
			// the HTML to the plain text. Store it into the variable.
			$pagedata = $h2t->get_text();
			
			echo strlen($pagedata)."\r\n";
			
			if(strlen($pagedata)>4000){
				$pagedata=substr ( $pagedata , 0, 4000 );
			}
			
			$qry="http://speller.yandex.net/services/spellservice/checkText?text=".urlencode($pagedata)."&lang=ru&format=plain&options=2103";
			//echo $qry;
			
			$q_result=file_get_contents($qry);
			//echo $q_result;
			
			if($q_result !=""){
				$dom= new SimpleXMLElement($q_result);
			
				$sp_err="";
				foreach ($dom->error as $node) {
					$word=$node->word;
					$len=$node->attributes()->len;
					$pos=$node->attributes()->pos;
					$row=$node->attributes()->row;
					$col=$node->attributes()->col;
					$sp_err=$sp_err.$word.";pos=".$pos.";row=".$row.";col=".$col."\r\n";
				}
				
				echo $sp_err;
				
				if($sp_err==""){
					$stmt = $db->prepare("UPDATE webpage SET spellresult='OK' WHERE pageid = ?");
					$stmt->bind_param('s', $result[$i]['pageid']);
					$stmt->execute();
					$stmt->close();
				}else{
					$stmt = $db->prepare('UPDATE webpage SET spellresult=? WHERE pageid = ?');
					$stmt->bind_param('ss', $sp_err, $result[$i]['pageid']);
					$stmt->execute();
					$stmt->close();
				}
			}else{
				$stmt = $db->prepare("UPDATE webpage SET spellresult='ERROR' WHERE pageid = ?");
				$stmt->bind_param('s',  $result[$i]['pageid']);
				$stmt->execute();
				$stmt->close();
			}
			
		}
		
		$db->close();
		echo("page speller finished.\r\n");
		
		
		
		
		
							   
?>