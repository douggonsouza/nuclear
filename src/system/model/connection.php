<?php

namespace Nuclear\system\model;

/**
 * deprecated class
 *
 */

use Nuclear\configs\cfg;

class connection
{

	static private $origem  = null;
	static private $destino = null;

	private function __construct(){	}
	
	/**
	 * Acesso à conexão origem
	 * @param \PDO $pdo
	 * @throws EngineException
	 */
	static public function origem(){
                   
        if(!isset(self::$origem)){
            
            $db = cfg::rescue('db');

            self::$origem = new \PDO(
				'mysql:='.$db['DB_ORIGEM_HOST'].';dbname='.$db['DB_ORIGEM_SCHEMA'],
                $db['DB_ORIGEM_LOGIN'],
                $db['DB_ORIGEM_SENHA'],
            	[\PDO::ATTR_PERSISTENT => true]);
            return (isset(self::$origem))? self::$origem: null;
        }
        return self::$origem;
	}
	
	/**
	 * Acesso à conexão destino
	 * @param \PDO $pdo
	 * @throws EngineException
	 */
	static public function destino()
	{    
        if(!isset(self::$destino)){

            $db = cfg::rescue('db');
                                        
            self::$destino = new \PDO(
				'mysql:host='.$db['DB_DESTINO_HOST'].';dbname='.$db['DB_DESTINO_SCHEMA'],
                $db['DB_DESTINO_LOGIN'],
                $db['DB_DESTINO_SENHA'],
                [\PDO::ATTR_PERSISTENT => true]);
            return (isset(self::$destino))? self::$destino: null;
        }
        return self::$destino;
	}
	
	/**
	 * Evento destruidor da classe
	 */
	function __destruct()
	{
		self::$origem = null;
		self::$destino = null;			
	}
	
}
