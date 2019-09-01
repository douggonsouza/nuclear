<?php

namespace system\model\actions;
	
	use system\model\actions\Action;
    use system\model\entity;
    use system\model\table;
    use system\model\actions\log;
    use system\model\a_entity;

	class Update implements Action{
		
		//----- PROPRIEDADES -----

		private $status = false;
        private $where  = null;
		
		//----- ASSOCIAÇÕES -----
		
		private $conn   = null;
		
		//----- MÉTODOS -----
		
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
		 * @param entity\Entity $entity
		 * @param int $action
		 */
		public function exec( $entity){
			
            try{

                // testa a tipagem
                if(isset($entity) && $entity instanceof a_entity){

                    // forma query
                    $sql = $this->queryString($entity);
                    // testa query
                    if(isset($sql) && strlen($sql) > 0){

                        // forma prepare de inclusão da entidade
                        $stt = $this->conn->prepare($sql);
                        // testa a execução da inclusão
                        $this->status = $stt->execute();
                        // log
                        $logs = orbe::rescue('logs');
                        $logs::setQuery($sql);
                        return $this->status;
                    }
                    else // diapara erro
                        throw new \Exception('Sem ação definida.');					
                }
                else	
                    throw new \Exception('Sem entidade.');
            }
            catch(\Exception $e)
            {

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
                
        private function queryString($entity){
	
            $sd      = ''; // variável de saída
            $pkValue = null;
            $pk      = null;
            // testa tipagem
            if(isset($entity)){

                $vector = $entity->getFields();
                // coleta dados tabela
                $table  = (isset($entity->getTable()))? $entity->getTable(): '';
                $where  = (isset($this->where))? $this->where: null;
                // testa tamanho do array
                if(isset($vector) && count($vector) > 0 && isset($table) && count($table) > 0){

                    $sd .= 'UPDATE '.$table.' SET';
                    foreach($vector as $chv => $vle){
                        
                        if(isset($vle['Value'])){
                        
                            // teste de exclusão da chave primária
                            if($vle['Key'] !== 'PRI'){

                                // define tipo
                                $type = explode('(',$vle['Type']);
                                switch($type[0]){
                                    // tipos string
                                    case 'varchar'   : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'char'      : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'blob'      : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'text'      : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'enum'      : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'set'       : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'tinytext'  : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'tinyblob'  : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'mediumtext': $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'mediumblob': $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'longblob'  : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;                                              
                                    // tipos date                                        
                                    case 'date'     : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'datetime' : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'timestamp': $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'time'     : $sd .= ' '.$vle['Field']."='".$vle['Value']."',"; break;
                                    case 'year'     : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    // tipos numericos                                            
                                    case 'integer'  : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'int'      : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'tinyint'  : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'numeric'  : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'decimal'  : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'smallint' : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'float'    : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'real'     : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'double'   : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'mediumint': $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;
                                    case 'bigint'   : $sd .= ' '.$vle['Field'].'='.$vle['Value'].','; break;                                            
                                }
                            }
                            else{

                                $pk      = $chv;
                                $pkValue = $vle['Value'];
                            }
                        
                        }
                    }
                    $sd = substr($sd,0,-1).' ';
                }
                // where
                if(isset($where))
                    $sd .= 'WHERE '.$where.';';
                else
                    $sd .= 'WHERE '.$pk.'='.$pkValue.';';
            }
            return $sd;	
		}
	}