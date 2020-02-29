<?php

namespace Nuclear\system\model;

use Nuclear\configs\cfg;

abstract class conn
{

	static private $connection  = null;

	private function __construct(){	}
	
	/**
	 * Acesso à conexão conn
	 * 
	 * @return
	 */
	static public function connection()
	{
        if(!isset(self::$connection)){
            
            $db = cfg::rescue('db');

			self::$connection = mysqli_connect(
				$db['DB_ORIGEM_HOST'],
				$db['DB_ORIGEM_LOGIN'],
				$db['DB_ORIGEM_SENHA'],
				$db['DB_ORIGEM_SCHEMA']
			) or die('Error connection database.');
		}
		return self::$connection;
    }
    	
	/**
	 * Evento destruidor da classe
	 */
	function __destruct()
	{
		self::$connection = null;	
	}
	

	/**
	 * Get the value of conn
	 */ 
	static public function getConnection()
	{
		self::connection();
		return self::$connection;
	}
}
