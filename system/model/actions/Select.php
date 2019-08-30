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
namespace system\model\actions;

use system\model\actions\Action;
use system\model\entity;
use system\model\table;
use system\model\actions\log;
use system\model\a_entity;
use app\orbe;

	class Select implements Action{

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
		public function __construct($db){

            // coleta conexão
            $this->conn = $db;
		}

		//----- FUNÇÕES -----

        /**
         * Define cláusula where
         *
         * @param string where
         */
        public function setWhere($where){
            if(isset($where))
                $this->where = $where;
            return $this;
        }
                
        /**
		 * Executa inserção da entity
		 * @param string $sql
		 * @param string $action
		 */
		public function exec($entity){

            try{

                // testa a tipagem
                if(isset($entity) && $entity instanceof a_entity){

                    // forma query
                    $sql = $this->queryString($entity);
                    // forma prepare de inclusão da entidade
                    $stt = $this->conn->prepare($sql);
                    // testa a execução da inclusão
                    try{
                        
                        if($stt->execute()){

                            $this->status = $stt->fetchAll(\PDO::FETCH_ASSOC);
                            // log
                            $logs = orbe::rescue('logs');
                            $logs::setQuery($sql);
                            return $this->status();
                        }
                    }
                    catch(\Exception $e){
                        
                        throw new \Exception($e->getMessage);
                    }
                }
                else
                    throw new \Exception('Sem data.');
            }
            catch(\Exception $e){

                // sob nível da chamada de erro
                throw new \Exception($e->getMessage());
            }
            return false;
		}

		/**
		 * Exporta stado da ação
		 * @return mixed
		 */
		public function status(){

            // testa status
            return $this->status;
		}

		/**
		 * Forma string do tipo query a partir do Entity
		 * @param entity\Entity $entity
		 * @param int $action
		 */                
        private function queryString($entity){

            $sd = ''; // variável de saída
            // testa tipagem
            if(isset($entity)){

                $vector  = $entity->getFields();
                // coleta dados tabela
                $table  = (isset($entity->getTable()))? $entity->getTable(): '';
                $where  = (isset($this->where))? $this->where: null;
                // testa tamanho do array
                if(isset($vector) && count($vector) > 0 && isset($table) && count($table) > 0){

                    $sd .= 'SELECT ';
                    // existe where;
                    if(!isset($where)){
                        
                        foreach($vector as $chv => $vle){

                            if(isset($vle['Value'])){
                        
                                // teste de exclusão da chave primária
                                if($vle['Key'] !== 'PRI'){

                                    // define tipo
                                    $type = explode('(',$vle['Type']);
                                    switch($type[0]){
                                        // tipos string
                                        case 'varchar'   : $sd .= ' '.$vle['Field'].","; break;
                                        case 'char'      : $sd .= ' '.$vle['Field'].","; break;
                                        case 'blob'      : $sd .= ' '.$vle['Field'].","; break;
                                        case 'text'      : $sd .= ' '.$vle['Field'].","; break;
                                        case 'enum'      : $sd .= ' '.$vle['Field'].","; break;
                                        case 'set'       : $sd .= ' '.$vle['Field'].","; break;
                                        case 'tinytext'  : $sd .= ' '.$vle['Field'].","; break;
                                        case 'tinyblob'  : $sd .= ' '.$vle['Field'].","; break;
                                        case 'mediumtext': $sd .= ' '.$vle['Field'].","; break;
                                        case 'mediumblob': $sd .= ' '.$vle['Field'].","; break;
                                        case 'longblob'  : $sd .= ' '.$vle['Field'].","; break;                                              
                                        // tipos date                                        
                                        case 'date'     : $sd .= ' '.$vle['Field'].","; break;
                                        case 'datetime' : $sd .= ' '.$vle['Field'].","; break;
                                        case 'timestamp': $sd .= ' '.$vle['Field'].','; break;
                                        case 'time'     : $sd .= ' '.$vle['Field'].","; break;
                                        case 'year'     : $sd .= ' '.$vle['Field'].','; break;
                                        // tipos numericos                                            
                                        case 'integer'  : $sd .= ' '.$vle['Field'].','; break;
                                        case 'int'      : $sd .= ' '.$vle['Field'].','; break;
                                        case 'tinyint'  : $sd .= ' '.$vle['Field'].','; break;
                                        case 'numeric'  : $sd .= ' '.$vle['Field'].','; break;
                                        case 'decimal'  : $sd .= ' '.$vle['Field'].','; break;
                                        case 'smallint' : $sd .= ' '.$vle['Field'].','; break;
                                        case 'float'    : $sd .= ' '.$vle['Field'].','; break;
                                        case 'real'     : $sd .= ' '.$vle['Field'].','; break;
                                        case 'double'   : $sd .= ' '.$vle['Field'].','; break;
                                        case 'mediumint': $sd .= ' '.$vle['Field'].','; break;
                                        case 'bigint'   : $sd .= ' '.$vle['Field'].','; break;                                            
                                    }
                                }
                                else{

                                    $pk      = $chv;
                                    $pkValue = $vle['Value'];
                                }

                            }										
                        }                               
                    }
                    $sd = substr($sd,0,-1);
                    $sd .= ' FROM '.$table;
                    (isset($where) && strlen($where) > 0)? $sd .= ' WHERE '.$where.';': $sd .= ' WHERE '.$pk.'='.$pkValue.';';
                }
            }
            return $sd;
		}

	}