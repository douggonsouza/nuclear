<?php

namespace Nuclear\system\model;

/**
 * deprecated class
 *
 */

use Nuclear\system\model\a_entity;

    /**
     * Entity
     * @author Douglas Gonçalves de Souza
     * @version 2 Developer000Bug000Correction000
     */
	class table extends a_entity{            
		
        //----- PROPRIEDADES -----

        private $infoTable = null;
        private $data       = array();
        private $table      = '';

        //----- MÉTODOS -----
        
        /**
         * Responde ao evento de construção da classe
         * 
         * @param string $table
         */
        public function __construct($table = null, $fields = null)
        {
            $this->table($table);
            if(isset($table))
                $this->infoTable = $this->getColumns($table);
            if(isset($fields))
                $this->setFields($fields);
        }
        
        /**
         * Carrega os campos
         * 
         * @param mixe fields
         */
        public function fields($fields = null, $value = null, $tipo = null)
        {
        	if(isset($this->infoTable) && isset($fields)) {
        		$this->infoTable = $this->getColumns($this->table);
                foreach($this->infoTable as $chv => $vle) {
                	if(array_key_exists($vle['Field'], $fields)) {
                    	if(isset($fields[$vle['Field']]))
                    		$this->infoTable[$chv]['Value'] = $fields[$vle['Field']];
                    	else
                    		$this->infoTable[$chv]['Value'] = null;
                    }
                }
                return $this;
            }
            if(isset($this->table)){
                $this->infoTable = $this->getColumns($this->table);
                foreach($this->infoTable as $chv => $vle) {
                    if(array_key_exists($vle['Field'], $fields)) {
                        if(isset($fields[$vle['Field']]))
                            $this->infoTable[$chv]['Value'] = $fields[$vle['Field']];
                        else
                            $this->infoTable[$chv]['Value'] = null;
                    }
                }
            }
            return $this;
        }

        /**
         * Carrega os campos
         * 
         * @param array $fields
         * 
         * @return object
         */
        private function setFields(array $fields = null)
        {
        	if(isset($this->infoTable) && isset($fields)) {
        		$this->infoTable = $this->getColumns($this->table);
                foreach($this->infoTable as $chv => $vle) {
                	if(array_key_exists($vle['Field'], $fields)) {
                        if(isset($fields[$vle['Field']])){
                            if(!is_array($fields[$vle['Field']])){
                                $this->infoTable[$chv]['Value'] = $fields[$vle['Field']];
                                continue;
                            }
                            $this->infoTable[$chv]['Value'] = $fields[$vle['Field']][0];
                            continue;
                        }
                    	else
                    		$this->infoTable[$chv]['Value'] = null;
                    }
                }
                return $this;
            }
            if(isset($this->table)){
                $this->infoTable = $this->getColumns($this->table);
                foreach($this->infoTable as $chv => $vle) {
                    if(array_key_exists($vle['Field'], $fields)) {
                        if(isset($fields[$vle['Field']]))
                            $this->infoTable[$chv]['Value'] = $fields[$vle['Field']];
                        else
                            $this->infoTable[$chv]['Value'] = null;
                    }
                }
            }
            return $this;
        }

        /**
         * Carrega os campos
         * 
         * @return  array fields
         */
        public function getFields(){
            return $this->infoTable;
        }
        
        /**
         * Define propriedade query
         * @param string $where
         */
        public function table($name) {
            if(isset($name))
                $this->table = $name;
            return $this;
        }

        /**
         * Define propriedade query
         * @param string $where
         */
        public function getTable() {
            return $this->table;
        }

        /**
         * Exporta o nome do campo de chave estrngeira
         * @return type
         */
        public function getPk(){
            return $this->key($this->infoTable);
        }
        
        /**
         * Exporta o nome do campo de chave estrngeira
         * @return type
         */
        public function key($info){
            foreach($info as $vle){
                if($vle['Key'] == 'PRI')
                    return $vle['Field']; 
            }
            return $this;
        }

        /**
         * Colhe as informações das colunas da tabela
         * 
         * @param string $table
         */
        public function getColumns($table)
        {
            if(isset($table))
                return orm::query('show columns from '.$table.';');
            throw new \Exception('Not found table for entity.');
        }
    }
?>
