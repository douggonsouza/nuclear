<?php

namespace Nuclear\system\model\actions;

/**
 * deprecated class
 *
 */

	use Nuclear\system\model\actions\Action;
    use Nuclear\system\model\entity;
    use Nuclear\system\model\table;
	
	class Delete implements Action{
		
		//----- PROPRIEDADES -----

		private $status = false;
        private $where  = null;
		
		//----- ASSOCIAÇÕES -----
		
		private $conn = null;
		
		//----- MÉTODOS -----
		
		/**
		 * Evento construtor da classe
		 * @param int $status_cod
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
            
                // forma query
                $sql = $this->queryString($entity);
                // testa query
                if(isset($sql) && strlen($sql) > 0){
                
                    // forma prepare de inclusão da entidade
                    $stt = $this->conn->prepare($sql);
                    // executa deleção
                    $this->status = $stt->execute();
                    return $this->status;
                }	
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
	
            $sd = ''; // variável de saída
            // testa tipagem
            if(isset($entity)){

                $vector  = $entity->getFields();
                // coleta dados tabela
                $table  = (isset($entity->getTable))? $entity->getTable(): '';
                $where  = (isset($this->where))? $this->where: null;
                // testa tamanho do array
                if(isset($vector) && count($vector) > 0 && isset($table) && count($table) > 0){

                    $contador = 0;
                    $sd .= 'DELETE FROM '.$table.' WHERE';
                    // existe where;
                    if(!isset($where)){
                        
                        foreach($vector as $chv => $vle){

                            // teste de exclusão da chave primária
                            if($vle['Key'] == 'PRI' && isset($vle['Value'])){
                                
                                if($contador > 0){

                                    // define tipo
                                    $type = explode('(',$vle['Type']);
                                    switch($type[0]){
                                        // tipos string
                                        case 'varchar'   : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'char'      : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'blob'      : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'text'      : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'enum'      : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'set'       : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'tinytext'  : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'tinyblob'  : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'mediumtext': $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'mediumblob': $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'longblob'  : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;                                              
                                        // tipos date                                        
                                        case 'date'     : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'datetime' : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'timestamp': $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'time'     : $sd .= ' AND '.$vle['Field']."='".$vle['Value']."'"; break;
                                        case 'year'     : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        // tipos numericos                                            
                                        case 'integer'  : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'int'      : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'tinyint'  : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'numeric'  : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'decimal'  : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'smallint' : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'float'    : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'real'     : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'double'   : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'mediumint': $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;
                                        case 'bigint'   : $sd .= ' AND '.$vle['Field'].'='.$vle['Value'].''; break;                                            
                                    }
                                }
                                else{

                                    // define tipo
                                    $type = explode('(',$vle['Type']);
                                    switch($type[0]){
                                        // tipos string
                                        case 'varchar'   : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'char'      : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'blob'      : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'text'      : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'enum'      : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'set'       : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'tinytext'  : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'tinyblob'  : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'mediumtext': $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'mediumblob': $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'longblob'  : $sd .= ' '.$chv."='".$vle['Value']."'"; break;                                              
                                        // tipos date                                        
                                        case 'date'     : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'datetime' : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'timestamp': $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'time'     : $sd .= ' '.$chv."='".$vle['Value']."'"; break;
                                        case 'year'     : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        // tipos numericos                                            
                                        case 'integer'  : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'int'      : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'tinyint'  : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'numeric'  : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'decimal'  : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'smallint' : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'float'    : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'real'     : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'double'   : $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'mediumint': $sd .= ' '.$chv.'='.$vle['Value'].''; break;
                                        case 'bigint'   : $sd .= ' '.$chv.'='.$vle['Value'].''; break;                                            
                                    }
                                }
                            }
                            $contador += 1;										
                        }                               
                    }
                    else
                        $sd .= $where;
                    $sd .= ';';
                }
            }
            return $sd;
		}
	}
