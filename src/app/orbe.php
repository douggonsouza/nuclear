<?php

namespace Nuclear\app;

use Nuclear\app\propertys;
    
abstract class orbe extends \stdClass
{
    //----- 
    static $name = null;
    static $propertys = null;
    static $class = [];
    
    //----- METHODS -----
    
    public function __construct()
    {            
        self::init();
    }

    static public function init(){
        // associações
        if(isset($_SESSION['propertys']))
            self::$propertys = unserialize($_SESSION['propertys']);
        else
            self::$propertys = new propertys();
    }

    /**
     * Guarda objeto na sessão
     * @param type $object
     * @return type
     */
    static public function add($name, $object)
    {            
        if(is_object($object) && isset($name) && strlen($name) > 0)
            self::$class[$name] = $object;
    }

    /**
     * Guarda objeto na sessão
     * @param type $object
     * @return type
     */
    static public function &rescue($name)
    {
        return (isset(self::$class[$name]))? self::$class[$name]: null;
    }
    
    /**
     * Cria nova propriedade no objeto dinâmico
     * @param string $name
     * @param type $value
     * @return type
     */
    static public function addProperty($name, $value)
    {
        // existe propriedade
        if(!is_object($value)){
            self::property()->add($name, $value);
            // salva objeto na sessão
            $_SESSION['propertys'] = serialize(self::$propertys);
        }
    }

    /**
     * Resgata propriedade dinâmica
     * @param string $name
     * @return type
     */
    static public function rescueProperty($name) {
        return (isset($name))? self::property()->rescue($name): null;
    }
	
	/**
     * Retorna propriedade dinâmica
     * @param string $name
     * @return type
     */
    static public function property() {
        return (isset($name))? self::$propertys: null;
    }
}

