<?php
include_once(dirname(__FILE__)  . '/' .'config.php');

// connect to DB
        $db = new mysqli($config['db']['server'], $config['db']['username'], $config['db']['password'],
                               $config['db']['database']);
							   
	   
		if ($db->connect_error) {
            throw new Exception('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        }
        $db->set_charset("utf8");
		
		
		$handle = @fopen("dict.txt", "r");
		$i=0;
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				$i++;
				if(($i %1000)==0){
					echo $buffer;
				}
				$buffer=str_ireplace(array("\n","\r"," "),"",$buffer);
				$stmt = $db->prepare('insert into dict(word) values(?)');
				$stmt->bind_param('s', $buffer);
				$stmt->execute();
				$stmt->close();
			}
			
			fclose($handle);
		}
		
		$db->close();
		echo("load main dictionary finished.\r\n");
		
		
		
		
		
							   
?>