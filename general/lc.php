<?php
include_once(dirname(__FILE__)  . '/' .'config.php');

// connect to DB
        $db = new mysqli($config['db']['server'], $config['db']['username'], $config['db']['password'],
                               $config['db']['database']);
							   
	   
		if ($db->connect_error) {
            throw new Exception('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        }
        $db->set_charset("utf8");
		
		
		$result = array();
       
        $res = $db->query('SELECT url,id FROM website WHERE  allowcheck=1');
        while ($row = $res->fetch_assoc()){
            $result[] = $row;
			
			$stmt = $db->prepare('UPDATE website SET lastcheck=now() WHERE id = ?');
            $stmt->bind_param('s', $row['id']);
            $stmt->execute();
            $stmt->close();
        }
        $res->close();
		
		
		 
		for($i=0;$i<count($result);$i++){
			echo('build map for '.$result[$i]['url']."\r\n");
			shell_exec("lcmap.cmd ".$result[$i]['url']." ".$result[$i]['id']);
			$map=file_get_contents($result[$i]['id'].'.map.xml');
			
			saveMap2DB($map,$result[$i]['id'],$db);	
			
			
			
			echo('build error list for '.$result[$i]['url']."\r\n");
			shell_exec("lc.cmd ".$result[$i]['url']." ".$result[$i]['id']);
			
			$err_xml=file_get_contents($result[$i]['id'].'.err.xml');
			
			saveErr2DB($err_xml,$result[$i]['id'],$db);	
			
		}
		
		$db->close();
		echo("links scan finished.\r\n");
		
		
		
		
		/////////////////////////////  functions //////////////////////////////
		function saveMap2DB($map,$siteid,$db){
		$dom= new SimpleXMLElement($map);
			
		
		foreach ($dom->graph->node as $node) {
			$url=$node->url;
			$label=$node->label;
			$ext=$node->data->extern;
			if ($ext==0){
				echo $label."->".$url."\r\n";
				$q="SELECT pageid FROM webpage WHERE url='".$url."' and site_id=".$siteid;		
				
				//echo $q;
				
				$res = $db->query($q);
				$row = $res->fetch_assoc();
				if($row){
					$stmt = $db->prepare('UPDATE webpage SET checktime=now() WHERE pageid = ?');
					$stmt->bind_param('s', $row['pageid']);
					$stmt->execute();
					$stmt->close();
				}else{
					$stmt = $db->prepare('insert into webpage(URL,site_id,checktime) values(?,?,now())');
					$stmt->bind_param('ss', $url,$siteid);
					$stmt->execute();
					$stmt->close();
				}
				$res->close();
			}
		}
	  }
	  
	    function saveErr2DB($err_xml,$siteid,$db){
		$dom= new SimpleXMLElement($err_xml);
			
			
		$stmt = $db->prepare('delete from weblink where page_id in (select pageid from webpage where site_id=?)');
		$stmt->bind_param('s', $siteid);
		$stmt->execute();
		$stmt->close();
		
		foreach ($dom->urldata as $node) {
			$url=$node->realurl;
			$pageurl=$node->parent;
			$v=$node->valid;
			$page_error= $v->attributes()->result;
			
			//echo $label."->".$url."\r\n";
			$q="SELECT pageid FROM webpage WHERE url='".$pageurl."' and site_id=".$siteid;		
			//echo $q;
			
			$res = $db->query($q);
			$row = $res->fetch_assoc();
			if($row){
				$stmt = $db->prepare('insert into weblink(URL,page_id,error) values(?,?,?)');
				$stmt->bind_param('sss', $url,$row['pageid'], $page_error );
				$stmt->execute();
				$stmt->close();
			}
			$res->close();
			
		}
	  }
							   
?>