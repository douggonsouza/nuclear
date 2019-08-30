<?php
/**
 * MVC
 *
 * Suporte à requisições WEB com MVC.
 * carregamento de classes semelhantes como propriedades.
 * @version 1.00000.00.00000
 * @copyright De Souza Informática - 2016
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
 *
 */

namespace vendor\douggs\nuclear\system\model;

use vendor\douggs\nuclear\system\model\orm;

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
    protected function getColumns($table)
    {
        if(isset($table))
            return orm::query('show columns from '.$table.';');
        throw new \Exception('Not found table for entity.');
    }
}

?>