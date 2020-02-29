<?php

namespace Nuclear\system\model;

/**
 * deprecated class
 *
 */

use Nuclear\system\model\orm;

abstract class a_entity{

    /**
     * Responde ao evento de construção da classe
     * 
     * @param string $table
     */
    abstract public function __construct($table = null);

	/**
     * Carrega os campos
     * 
     * @param mixe fields
     */
    abstract public function fields($fields = null, $value = null, $tipo = null);

    /**
     * Carrega os campos
     * 
     * @return  array fields
     */
    abstract public function getFields();

    /**
     * Expoe propriedade Query
     * 
     * @return type
     */
    abstract public function table($name);

    /**
     * Define propriedade query
     * @param string $where
     */
    abstract public function getTable();

    /**
     * Exporta o nome do campo de chave estrngeira
     * @return type
     */
    abstract public function getPk();

    /**
     * Exporta o nome do campo de chave estrngeira
     * @return type
     */
    abstract public function key($info);

    /**
     * Colhe as informações das colunas da tabela
     * 
     * @param string $table
     */
    abstract public function getColumns($table);
}

?>
