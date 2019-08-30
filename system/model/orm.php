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

namespace vendor\nuclear\system\model;

use vendor\nuclear\system\model\entity;
use vendor\nuclear\system\model\table;
use vendor\nuclear\system\model\actions as action;

abstract class orm
{

    private $origem  = null;
    private $entity  = null;
    static  $conn    = null;
    static  $model   = null;
    
    public function __construct($model = null)
    {
        if(isset($model))
            self::$model = $model;
        $this->__start();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function __start()
    {
        // coleta conexão
        self::$conn = db::origem();
    }

    public function getConn()
    {
        if(!isset(self::$conn))
            self::__start();
        return self::$conn;
    }

    /**
     * Define a variável model
     *
     * @param string $local
     * @return void
     */
    static public function setModel( $local){
        if(isset($local))
            self::$model = $local;
    }
    
    /**
     * Executa inclusão da entidade
     * @param entity $entity
     * @return type
     */
    final public function insert(a_entity $entity){
        $insert = new action\Insert(self::getConn());
        return $insert->exec($entity);
    }
    
    /**
     * Executa atualização da entidade
     * @param entity $entity
     * @return type
     */
    final public function update(a_entity $entity){
        $update = new action\Update(self::getConn());
        return $update->exec($entity);
    }
    
    /**
     * Executa deleção da entidade
     * @param entity $entity
     * @return type
     */
    final public function delete(a_entity $entity){
        $delete = new action\Delete(self::getConn());
        return $delete->exec($entity);
    }
    
    /**
     * Executa seleção da entidade
     * @param entity $entity
     * @param string $where
     * @return type
     */
    final public function select(a_entity $entity){
        $select = new action\Select(self::getConn());
        return $select->exec($entity);
    }
    
    /**
     * Executa query no datasource origem
     * @param string $where
     * @return type
     */
    final public function query($sql){
        $query = new action\Query(self::getConn());
        return $query->exec($sql);
    }
    
    /**
     * Executa chamada da entidade
     * @param entity $entity
     * @return type
     */
    final public function call(a_entity $entity){
        $call = new action\Call(self::getConn());
        return $call->exec($entity);
    }
    
    /**
     * Inicia transação
     * @return boolean
     * @throws EngineException
     */
    final public function beginTransaction()
    {
        // desabilita autocommit
        self::$conn->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0 );
        // inicia transação
        self::$conn->beginTransaction();
        return true;
    }
    

    /**
     * Faz commit na transação iniciada
     * @return boolean
     * @throws EngineException
     */
    final public function commitTransaction()
    {
        // confirma transação
        self::$conn->commit();
        // =habilita autocommit
        self::$conn->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1 );
        return true;
    }
    

    /**
     * Faz rollback na transação iniciada
     * @return boolean
     * @throws EngineException
     */
    final public function rollbackTransaction()
    {
        // desfaz transação
        self::$conn->rollBack();
        // habilita autocommit
        self::$conn->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1 );
        return true;
    }
    

    /**
     * Exporta objeto tipo entity
     * @return entity
     */
    final public function entity(){
        return new entity();
    }
    

    /**
     * Exporta objeto tipo table
     * @return table
     */
    final public function table($table,$dados = null){
        return new table($table,$dados);
    } 
    
    
    /**
     * Expoe classe model
     * @return type
     */
    static public function model($name)
    {
        if(isset($name) && strlen($name) > 0){
            $model = str_replace(
                array('/','//','\\','\\\\'),
                '/',
                self::$model.DS.$name.'.php');
            if(file_exists($model)){
                $local = str_replace(
                    array('/','//','\\','\\\\'),
                    '\\',
                    self::$model.DS.$name);
                return new $local();
            }
            throw new \Exception('Not found model '.$local.'.');
        }
        return null;
    }
    
    /**
     * Identifica e exporta local para a pasta
     */
    // private function location($local, $path = CONTROLLER){
        
    //     $saida = '';
    //     $view = $this->localPathDefaultSystem($path);
    //     // existe local e ultimo controller
    //     if(isset($local)) {                
            
    //         // existe entrada
    //         if(strlen($local) > 0){
    
    //             // formata local
    //             $local = str_replace(array('\\','/'),'/',$local);
    //             // passado a localiza��o do template a partir da pasta base (view) ~/
    //             if((preg_match('/^(~\/)/m',$local,$match))){
    
    //                 //reformata arquivo
    //                 $arq = str_replace(array('\\','/'),DS,$local);
    //                 $saida = $view.substr($arq,1,strlen($arq));
    //             }
    //             elseif((preg_match('/^(~)/m',$local,$match))){ // passado o nome do template com ou sem extens�o - ~
    
    //                 //reformata arquivo
    //                 $arq = str_replace(array('\\','/'),DS,$local);
    //                 $saida = $view.DS.substr($arq,1,strlen($arq));
    //             }
    //             else{ // passado o caminho completo - :
    
    //                 //reformata arquivo
    //                 $arq = str_replace(array('\\','/'),DS,$local);
    //                 $saida = $arq;
    //             }
    //         }
    //     }
    //     return $saida;
    // }
	
}