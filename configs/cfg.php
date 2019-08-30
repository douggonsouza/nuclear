<?php

/**
 * CONFIG
 * @copyright Copyright (C) 2019 Gonçalves Informática. All rights reserved.
 * @license Copyright (C) 2019 Gonçalves Informática. All rights reserved.
 * @author Gonçalves Informática <douggonsouza@gmail.com>
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
*/

namespace vendor\nuclear\configs;

abstract class cfg
{

    static $cfg = [];
    static $key;

    /**
     * Undocumented function
     *
     * @param [type] $key
     * @return void
     * 
     */
    static public function rescue($key = null)
    {
        self::setKey($key);
        if(null !== self::getKey())
            return self::$cfg[self::getKey()];
        return self::$cfg;
    }

    /**
     * Exemplo
     * $cfg = [
     *      'root' => [
     *          'default' => 'main']
     * ]
     */
    static public function add(array $cfg, $index = null)
    {
        if(isset($cfg) && is_array($cfg) && !isset($index)){
            try{
                self::$cfg = array_merge (self::$cfg,$cfg);
                return true;
            }
            catch(\Exception $ee){
                return false;
            }
        }
        try{
            if(isset(self::$cfg[$index]))
                self::$cfg[$index] = array_merge (self::$cfg[$index],$cfg);
            if(!isset(self::$cfg[$index]))
                self::$cfg[$index] = $cfg;
        }
        catch(\Exception $ee){
            return false;
        }
        return true;
    }

    /**
     * Get the value of key
     */ 
    static public function getKey()
    {
        return self::$key;
    }

    /**
     * Set the value of key
     *
     * @return  self
     */ 
    static public function setKey($key)
    {
        if(isset($key) && !empty($key))
            self::$key = $key;

        return self;
    }
}



