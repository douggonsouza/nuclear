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

namespace vendor\douggs\nuclear\system;

	use vendor\douggs\nuclear\system\control\controller;
	use vendor\douggs\nuclear\system\mask_action\mask_action;
	use vendor\douggs\nuclear\system\request\request;
	use vendor\douggs\nuclear\loader\loader;
	use vendor\douggs\nuclear\configs\cfg;
	use vendor\douggs\nuclear\app\orbe;
	use vendor\douggs\nuclear\system\view\display;
	   
	class system
	{
		  
		private $controller = null;
		private $rota       = null;
		private $folderMVC  = '';
		private $notFound   = null;		  
    
		private $mask_action = null;
		  
    
		/**
		 * Evento construtor da classe
		 * @param string $url
		 */
		public function __construct()
		{
			$this->mask_action = new mask_action();
			$this->request = new request();
		} 
    
    
		public function setPathMVC($folder = null)
		{
			if(isset($folder) && strlen($folder) > 0)
				$this->folderMVC = $folder;
			define('PATHMVC', $this->folderMVC);
		} 
    
		  
		/**
		 * Direciona requisi��o para o action do controller
		 * @param string $base
		 * @param string $url
		 */
		public function roteiaRequest($url = null)
		{
			$extension = $this->request->extension($url);
			if(isset($extension) && strlen($extension) > 0){
				try{
					$extensionClear = str_replace(
					cfg::getCfg('root')['file_job'],
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
			controller::addRequest($this->request);
            if(!$this->request->isError){
				$pathRoot = str_replace(
					array('/','//','\\','\\\\'),
					'/',
					$this->request->localController.'.php');
				if($this->request->isDirect){
					$pathRoot = $this->request->localFile;
					$pathRoot = str_replace(
						array('/','//','\\','\\\\'),
						'/',
						$pathRoot);
				}
                if(file_exists($pathRoot)){
					if($this->request->isDirect){
						$display = new display();
						$display->render($this->request);
						die();
					}
					$pathController = $this->request->controller;
                    $this->controller = new $pathController();                            
                    if(isset($this->controller)){
                        // mascara
                        $mask_action = $this->mask_action->existMask(
							substr($this->request->controller,0,strlen($this->request->controller)).'/'.$this->request->action
						);
                        if(isset($mask_action) && strlen($mask_action) != false){
							return $this->mask_action->exec_action(
								$this->controller,
								$this->rota,
								$mask_action);
						}

						// seta o template
						$this->controller->template($this->request->action);

						// aplica arguments as actions
						$this->applyArguments(
							$this->controller,
							$this->request->action,
							$this->request->arguments
						);
						return true;							
                    }
                }
			}
			header("location:".$this->notFound);
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
		private function applyArguments($controller, $action, $arguments)
		{
			// disparo anterior a ação
			$controller->__before();

			$countArguments = count($arguments);
			switch($countArguments){
				case 0:
					$controller->$action();
				break;
				case 1:
					$controller->$action($arguments[0]);
				break;
				case 2:
					$controller->$action(
						$arguments[0],
						$arguments[1]
					);
				break;
				case 3:
					$controller->$action(
						$arguments[0],
						$arguments[1],
						$arguments[2]
					);
				break;
				case 4:
					$controller->$action(
						$arguments[0],
						$arguments[1],
						$arguments[2],
						$arguments[3]
					);
				break;
				case 5:
					$controller->$action(
						$arguments[0],
						$arguments[1],
						$arguments[2],
						$arguments[3],
						$arguments[4],
						$arguments[5]
					);
				break;
				case 6:
				$controller->$action(
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
				$controller->$action(
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
				$controller->$action(
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
				$controller->$action(
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

			// disparo anterior a ação
			$controller->__after();
		}    
}