<?php

namespace Nuclear\system\model\actions;
	
	use Nuclear\system\model\actions\Action;
    use Nuclear\system\model\entity;

	class Call implements Action{
		
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
		 * @param int $action
		 */
		public function exec($entity){
			
            try{
            
                // forma query
                $sql = $this->queryString($entity);
                // forma prepare de inclusão da entidade
                $stt = $this->conn->prepare($sql);						
                // testa a execução da inclusão
                if($stt->execute()){
                
                    $this->status = $stt->fetchAll(\PDO::FETCH_ASSOC);
                    return true;
                }
                else
                    throw new \Exception('Sem ação.');
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

                        $vector = $entity->getFields();
                        $table  = $entity->getTable();
                        // testa tamanho do array
                        if(isset($vector) && count($vector) > 0 && isset($table) && count($table) > 0){

                            $sd .= 'CALL '.$table.'(';
                            foreach($vector as $chv => $vle){

                                ($vle['type'] == 'string' || $vle['type'] == 'date')? $sd .= "'".$vle['value']."',": $sd .= ''.$vle['value'].',';							
                            }
                            $sd = substr($sd,0,-1).' ';
                            $sd .= ');';		
                        }
                    }
                    return $sd;		
		}
		
	}