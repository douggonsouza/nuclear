<?php

namespace vendor\douggs\nuclear\system\model;

use vendor\douggs\nuclear\system\model\a_entity;
        
    /**
     * Entity
     * @author Douglas Gonçalves de Souza
     * @version 2 Developer000Bug000Correction000
     */
	class entity extends a_entity{
            
        const string  = 'string';
        const integer = 'integer';
        const int     = 'integer';
        const float   = 'float';
        const decimal = 'decimal';
        const date    = 'date';
		
        //----- PROPRIEDADES -----

        private $table  = null;
        private $key    = null;
        private $fields = array();
        private $where  = '';

        //----- MÉTODOS -----
	
        /**
         * Responde ao evento de construção da classe
         * 
         * @param string $table
         */
        public function __construct($table = null){
            $this->table($table);
        }

        /**
         * Define o nome da tabela
         * @param string $name
         * @return system\model\entity
         */
        public function table($name){
            if(isset($name) && strlen($name) > 0)
                $this->table = $name;
            return $this;
        }
        
        /**
         * Define o campo que é chave extrangeira
         * @param string $name
         * @return system\model\entity
         */
        public function setPk($name){
            
            if(isset($name) && strlen($name) > 0)
                $this->key = $name;
            return $this;
        }
        
        /**
         * Carrega os campos
         * @param string $name
         * @param string $value
         * @param string $tipo
         * @return system\model\entity
         */
        public function fields($fields = null, $value = null, $tipo = null){
            
            switch($tipo){
                
                case 'string':
                case 'date':
                    $this->fields[$fields] = array( 'Field' => $fields,
                                                    'Value' => (string) $value,
                                                    'Type'  => $tipo);
                    break;
                case 'integer':
                case 'float':
                case 'decimal':
                    $this->fields[$fields] = array( 'Field' => $fields,
                                                    'Value' => (string) $value,
                                                    'Type'  => $tipo);
                    break;
                case null:
                    $this->fields[$fields] = array( 'Field' => $fields,
                                                    'Value' => null,
                                                    'Type'  => null);
                    break;
                default:
                    $this->fields[$fields] = array( 'Field' => $fields,
                                                    'Value' => null,
                                                    'Type'  => null);
                    break;
            }
            return $this;
        }
        
        /**
         * Define condições
         * @param string $where
         */
        public function setWhere($where){
            
            $this->where = (isset($where) && strlen($where) > 0)? $where: '';
            return $this;
        }
        
        /**
         * Exporta o nome da tabela
         * @return type
         */
        public function getTable(){            
            return $this->table;
        }
        
        /**
         * Exporta o nome do campo de chave estrngeira
         * @return type
         */
        public function getPk() {
            return parent::key($this->fields);
        }
        
        /**
         * Exporta array de campos
         * @return type
         */
        public function getFields(){
            
            return $this->fields;
        }
        
        /**
         * Coleta condição
         */
        public function getWhere(){
            
            return $this->where;
        }
        
        /**
         * Exporta array de campos
         * @return type
         */
        public function getField($field){
            
            // existe indice
            if(key_exists($field,$this->fields))
                return $this->fields[$field]['value'];
            return null;
        }
    }
?>