<?php

namespace vendor\douggs\nuclear\system;

use vendor\douggs\nuclear\system\control\act;
use vendor\douggs\nuclear\system\request\request;
use vendor\douggs\nuclear\loader\loader;
use vendor\douggs\nuclear\configs\cfg;
use vendor\douggs\nuclear\app\orbe;
use vendor\douggs\nuclear\system\view\display;
   
class routing
{
	  
	private $controller = null;
	private $rota       = null;
	private $folderMVC  = '';
	private $notFound   = null;
	protected $pathController = null;
   
	/**
	 * Evento construtor da classe
	 * @param string $url
	 */
	public function __construct()
	{
		$this->request = new request();
	}    
	  
	/**
	 * Direciona requisiÃ§Ã£o para o action do controller
	 * @param string $base
	 * @param string $url
	 */
	public function routes($url = null)
	{
		// verifica existencia de extensÃ£o
		// define requisiÃ§Ã£o direta
		$extension = $this->request->extension($url);
		if(isset($extension) && strlen($extension) > 0){
			try{
				$extensionClear = str_replace(
				cfg::rescue('root')['file_job'],
				'',
				$extension);
				if($extensionClear !== "")
					$this->request->isDirect = true;
			}
			catch(\Exception $e){
				throw new \Exception($e->getMessage());
			}
		}

		// define as rotas
		$this->request = $this->request->urlRoutes($url);

		// guarda objeto com as rotas na controller
		act::addRequest($this->request);
        if(!$this->request->isError){
			$this->pathController = str_replace(
				array('/','//','\\','\\\\'),
				'/',   
				$this->request->localController.$this->request->action.'.php');
           
			// carrega endereÃ§o de requisiÃ§Ã£o direta
			if($this->request->isDirect){
				$this->pathController = $this->request->localFile;
				$this->pathController = str_replace(
					array('/','//','\\','\\\\'),
					'/',  
					$this->pathController);
			}       
           
			// objetiva controller e action
            if(file_exists($this->pathController)){
				if($this->request->isDirect){
					$display = new display();
					$display->render($this->request);
					die(); 
				}       
				
				$fileAct = str_replace(
					array('/','//','\\','\\\\'),
					'\\',   
					$this->request->controller.$this->request->action);
                $this->act = new $fileAct();                            
                if(isset($this->act)){
            
					// seta o template
					$this->act->setTemplate($this->request->action);
            
					// aplica arguments as actions
					$this->applyArguments(
						$this->act,
						$this->request->arguments
					);     
					return true;							
                }
           }
		}
		header("location:".$this->request->notFound);
        return false;
	} 

	/**
	 * Aplica argumentos nas actions
	 *
	 * @param string $controller
	 * @param string $action
	 * @param string $arguments
	 * @return void
	 */
	private function applyArguments($controller, $arguments)
	{
		// disparo anterior a aÃ§Ã£o
		$controller->__before();

		$countArguments = count($arguments);
		switch($countArguments){
			case 0:
				$controller->main();
			break;
			case 1:
				$controller->main($arguments[0]);
			break;
			case 2:
				$controller->main(
					$arguments[0],
					$arguments[1]
				);
			break;
			case 3:
				$controller->main(
					$arguments[0],
					$arguments[1],
					$arguments[2]
				);
			break;
			case 4:
				$controller->main(
					$arguments[0],
					$arguments[1],
					$arguments[2],
					$arguments[3]
				);
			break;
			case 5:
				$controller->main(
					$arguments[0],
					$arguments[1],
					$arguments[2],
					$arguments[3],
					$arguments[4],
					$arguments[5]
				);
			break;
			case 6:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6]
			);
			break;
			case 7:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6],
				$arguments[7]
			);
			break;
			case 8:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6],
				$arguments[7],
				$arguments[8]
			);
			break;
			case 9:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6],
				$arguments[7],
				$arguments[8],
				$arguments[9]
			);
			break;
		}

		// disparo anterior a aÃ§Ã£o
		$controller->__after();
	}    
}