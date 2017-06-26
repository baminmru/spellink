<?php
// подключаем файл конфигурации
include_once('config.php');

class Database 
{
	public static $config;
	
	
	private static $cont  = null;
	
	public function __construct() {
		
	
	}
	
	
	public static function connect()
	{
	
		global $config;
		
        self::$config = $config;
	
	   
       if ( null == self::$cont )
       {      
        try 
        {
		  // подключение с учетом параметров из файла конфигурации		
          self::$cont =  new PDO( "mysql:host=".self::$config['db']['server'].";"."dbname=".self::$config['db']['database'], self::$config['db']['username'], self::$config['db']['password']);  
        }
        catch(PDOException $e) 
        {
          die($e->getMessage());  
        }
       } 
	  
	  //  установка начальных параметров  сессии для решения проблем с русскими буквами
	   self::$cont->exec("Set charset utf8");
  	   self::$cont->exec("Set character_set_client = utf8");
	   self::$cont->exec("Set character_set_connection = utf8");
	   self::$cont->exec("Set character_set_results = utf8");
	   
	    self::$cont->exec("SET NAMES 'utf8';");
		self::$cont->exec("SET CHARACTER SET 'utf8';");
		
		self::$cont->exec("Set collation_connection = utf8_general_ci");
		self::$cont->exec("SET SESSION collation_connection = 'utf8_general_ci';");
	   
	   
       return self::$cont;
	}
	
	public static function disconnect()
	{
		self::$cont = null;
	}
}
?>