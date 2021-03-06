<?php

namespace Nuclear\configs;

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



