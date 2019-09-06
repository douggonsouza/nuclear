<?php

namespace system\model\actions;

	use system\model\actions\Action;
    use system\model\entity;
    use system\model\table;
	
	class Insert implements Action{
		
		//----- PROPRIEDADES -----
		
		private $status = false;
        private $where  = null;
		
		//----- ASSOCIAÇÕES -----
		
		private $conn = null;
		
		//----- MÉTODOS -----
		
		/**
		 * Evento construtor da classe
		 * @param entity\Entity $entity
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
		 * @param entity\Entity $entity
		 * @param int $action
		 */
		public function exec($entity){
            try{

                // testa a tipagem
                if(isset($entity) && $entity instanceof a_entity){

                    // forma query
                    $sql = $this->queryString($entity);
                    //
                    // testa query
                    if(isset($sql) && strlen($sql) > 0){
                        
                        // forma prepare de inclusão da entidade
                        $stt = $this->conn->prepare($sql);
                        try{
                            
                            // testa a execução da inclusão
                            if($stt->execute()){
                                
                                // forma query MAX key
                                $sql = 'SELECT
                                            MAX(`'.$entity->getPk().'`) AS '.$entity->getPk().'
                                        FROM
                                            '.$entity->getTable().'
                                        LIMIT 0,1;';
                                
                                // prepara consulta MAX
                                $stt = $this->conn->prepare($sql);
                                if($stt->execute()){

                                    $rst = $stt->fetchAll(\PDO::FETCH_ASSOC);
                                    $this->status = (int) $rst[0][$entity->getPk()];
                                    return $this->status;
                                }
                            }
                            else
                                $err = $stt->errorInfo();
                                throw new \Exception('PDO/ERROR: '.$err[2].': '.$err[1].': '.$sql);
                        }
                        catch (\Exception $e) {                                    
                            
                            throw new \Exception($e->getMessage());
                        }
                    }
                    else
                        throw new \Exception('Dados insuficientes.');
                }
                else
                    throw new \Exception('Sem entidade.');

            }
            catch(\Exception $e)
            {

                // sob nível da chamada de erro
                die(var_dump( $e->getMessage() ));
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
		 * @param $entity
		 * @param int $action
		 */                
        private function queryString($entity){
			
    		$sd = ''; // variável de saída
    		// testa tipagem
    		if(isset($entity)){
    			
                // coleta dados campos
                $vector = $entity->getFields();
                // coleta dados tabela
                $table  = ($entity->getTable() !== null)? $entity->getTable(): ''; 
                // testa tamanho do array
                if(isset($vector) && count($vector) > 0 && isset($table) && count($table) > 0){
    				
        			$sd .= 'INSERT INTO '.$table.' SET';
        			foreach($vector as $chv => $vle){
                                            
                        // teste de exclusão da chave primária
                        if($vle['Key'] !== 'PRI' && isset($vle['Value'])){
                                
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
        			}
        			$sd = substr($sd,0,-1).';';
                }
    		}
    		return $sd;
	    }
}