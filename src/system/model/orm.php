<?php

namespace Nuclear\system\model;

use Nuclear\system\model\connection;
use Nuclear\system\model\entity;
use Nuclear\system\model\table;
use Nuclear\system\model\actions\Query;
use Nuclear\system\model\actions\Insert;
use Nuclear\system\model\actions\Update;
use Nuclear\system\model\actions\Delete;
use Nuclear\system\model\actions\Select;
use Nuclear\system\model\actions\Call;


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
        if(!isset(self::$conn))
            self::$conn = connection::origem();
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
        $insert = new Insert(self::getConn());
        return $insert->exec($entity);
    }
    
    /**
     * Executa atualização da entidade
     * @param entity $entity
     * @return type
     */
    final public function update(a_entity $entity){
        $update = new Update(self::getConn());
        return $update->exec($entity);
    }
    
    /**
     * Executa deleção da entidade
     * @param entity $entity
     * @return type
     */
    final public function delete(a_entity $entity){
        $delete = new Delete(self::getConn());
        return $delete->exec($entity);
    }
    
    /**
     * Executa seleção da entidade
     * @param entity $entity
     * @param string $where
     * @return type
     */
    final public function select(a_entity $entity){
        $select = new Select(self::getConn());
        return $select->exec($entity);
    }
    
    /**
     * Executa query no datasource origem
     * @param string $where
     * @return type
     */
    final public function query($sql){
        $query = new Query(self::getConn());
        return $query->exec($sql);
    }
    
    /**
     * Executa chamada da entidade
     * @param entity $entity
     * @return type
     */
    final public function call(a_entity $entity){
        $call = new Call(self::getConn());
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
     * Export seleção da entidade
     * @return type
     */
    final public function getSelect(){
        return new Select(self::getConn());
    }

    /**
     * Export query da entidade
     * @return type
     */
    final public function getQuery(){
        return new Query(self::getConn());
    }

    /**
     * Export insert da entidade
     * @return type
     */
    final public function getInsert(){
        return new Insert(self::getConn());
    }

    /**
     * Export update da entidade
     * @return type
     */
    final public function getUpdate(){
        return new Update(self::getConn());
    }

    /**
     * Export delete da entidade
     * @param entity $entity
     * @param string $where
     * @return type
     */
    final public function getDelete(){
        return new Delete(self::getConn());
    }

    /**
     * Export call da entidade
     * @param entity $entity
     * @param string $where
     * @return type
     */
    final public function getCall(){
        return new Call(self::getConn());
    }
	
}
