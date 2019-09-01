<?php

namespace system\model\actions;

use system\model\actions\Action;
use system\model\entity;
use system\model\actions\log;
use system\model\a_entity;
use app\orbe;

class Query implements Action
{

	//----- PROPRIEDADES -----
 
	private $status = false;
    private $where  = null;
 
	//----- ASSOCIAÇÕES -----
 
	private $conn = null;
 
	//----- MÉTODOS -----
 
	/**
	 * Evento construtor da classe
	 * @param string $sql
	 */
    public function __construct($db)
    {
           // coleta conexão
           $this->conn = $db;
	}
 
	//----- FUNÇÕES -----
 
    /**
     * Define cláusula where
     *
     * @param string where
     */
    public function setWhere($where)
    {
        if(isset($where))
            $this->where = $where;
        return $this;
    }
 
	/**
	 * Executa inserção da entity
	 * @param string $sql
	 */
    public function exec($query)
    {
        // testa a tipagem
        if(isset($query) && strlen($query) > 0){
            // forma prepare de inclusão da entidade
            $stt = $this->conn->prepare($query);
            if($stt->execute()){
                $this->status = $stt->fetchAll(\PDO::FETCH_ASSOC);
                // log
                $logs = orbe::rescue('logs');
                $logs::setQuery($query);
                return $this->status();
            }
        }
        else
            throw new \Exception('Sem data.');
        
        return false;
	}
 
	/**
	 * Exporta stado da ação
	 * @return mixed
	 */
    public function status()
    {
        // testa status
        return $this->status;
	}

}

?>