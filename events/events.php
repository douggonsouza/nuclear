<?php

namespace vendor\douggs\nuclear\events;

    use vendor\douggs\nuclear\events\dir_xml;
    use vendor\douggs\nuclear\events\assync;

    class events extends assync{
        
        //----- PROPERTS ------
        
        private $observers     = array();
        private $contentFolder = null;
        private $controller    = null;
        private $service       = null;
        
        //----- ASSOCIATIONS -----
        
        private $dir = null;
        
        //----- METHODS -------
        
        public function __construct($localObservers = null){
            
            // carrega observers
            $this->getContentFolder($localObservers);
        }
        
        //----- FUNCTIONS -----        
        
        /**
         * Dispara determinado evento
         * @param string $event
         * @param mixed $param
         */
        public function shootingEvent($event,$param = null){
        	
            // existe observers
            if(count($this->observers) > 0){
            	
                // Varre controllers
                foreach($this->observers as $index => $content){
                	
                	// existe evento
                	if($index == $event){
                		
	                	// varre observers
	                    foreach($this->observers as $observer){
	                    	
	                    	// executa funcao
	                    	$this->shooting_async($observer,$param);
	                    }	
                	}                    
                }
                return true;
            }
            return false;
        }
        
        /**
         * Executa determinada função de forma sincrona
         * @param string $class
         * @param string $function
         * @param string $param
         */
        private function shooting($class,$function,$param){
            
            // existe variaveis de entrada
            if(isset($class) && isset($function) && isset($param)){
                
                // variaveis de entrada n�o vazias
                if(is_string($class) && is_string($function)){
                    
                    try{
                        
                        // carrega classe
                        return new $class();
                        // dispata a��o
                        $observer->{$function}($param);
                        return;
                    }
                    catch(EnghineException $ee){
                        
                        throw new Exception(    $ee->getMessage(),
                                                $ee->getCode(),
                                                $ee->getFile(),
                                                $ee->getPrevious());
                    }
                }
            }
            return false;
        }
        
        /**
         * Executa determinada função de forma assyncrona
         * @param string $class
         * @param string $function
         * @param string $param
         */
        private function shooting_async($url, $param = null){
        
        	// variaveis de entrada nao vazias
        	if(is_string($url)){
        		
        		// dispara servi�o assincrono
        		return parent::call($url, $param);
        	}
        	return false;
        }
        
        /**
         * Carrega observers
         */
        private function getContentFolder($localObservers = null){
            
            // existe local
            if(isset($localObservers)){

                $this->dir = new dir_xml(); // inicia associação
                // coleta conteúdo de observers
                $this->contentFolder = $this->dir->folder_contents($localObservers);
                // existe conteúdo no arquivo
                if(isset($this->contentFolder) && count($this->contentFolder) > 2){
                    
                    // varre a pasta de xmls
                    foreach($this->contentFolder as $vle){
                    	
                        // existe xml
                        if(strpos($vle,'.xml') != false){
                            
                            $xmlFile = simplexml_load_file($localObservers.$vle);
                            $events  = get_object_vars($xmlFile);
                            foreach($events as $index => $content){
                            	
                            	$observers = get_object_vars($content);
	                            // existe objeto
	                            if(isset($observers))
	                                $this->observers[$index] = $observers['observer']; 
                            }                            
                        }
                    }                
                }            
            }
        }
    }

?>